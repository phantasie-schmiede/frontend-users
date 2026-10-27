<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Service;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PSBits\Foundation\Service\FrontendUrlService;
use PSBits\Foundation\Service\GlobalVariableService;
use PSBits\FrontendUsers\Data\ExtensionInformation;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Domain\Model\Typo3\Page;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use PSBits\FrontendUsers\Service\ValidationService;
use PSBits\FrontendUsers\Tests\Unit\Fixtures\FakeLanguageServiceFactory;
use ReflectionProperty;
use RuntimeException;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Localization\Locale;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Frontend\Page\PageInformation;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class ValidationServiceTest
 *
 * The frontend context is simulated with a frontend request, the logged in user is served from the pre-seeded
 * global variable cache, and the language stack is stubbed via singletons for the redirect flash message.
 *
 * @package PSBits\FrontendUsers\Tests\Unit\Service
 */
class ValidationServiceTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    private FlashMessageService $flashMessageService;
    private FrontendUrlService  $frontendUrlService;
    private ValidationService   $validationService;

    protected function setUp(): void
    {
        parent::setUp();

        $languageService = $this->createMock(LanguageService::class);
        $languageService->method('getLocale')
            ->willReturn(new Locale('en'));

        // v14 translates through LanguageService::translate(), v13 through sL().
        if (method_exists(LanguageService::class, 'translate')) {
            $languageService->method('translate')
                ->willReturn('Please log in.');
        } else {
            $languageService->method('sL')
                ->willReturn('Please log in.');
        }

        GeneralUtility::setSingletonInstance(
            LanguageServiceFactory::class,
            new FakeLanguageServiceFactory($languageService)
        );

        $cache = $this->createMock(FrontendInterface::class);
        $cache->method('get')
            ->willReturn($languageService);

        $cacheManager = $this->createMock(CacheManager::class);
        $cacheManager->method('getCache')
            ->willReturn($cache);

        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManager);

        $this->flashMessageService = $this->createMock(FlashMessageService::class);
        $this->frontendUrlService  = $this->createMock(FrontendUrlService::class);
        $this->validationService   = new ValidationService(new ExtensionInformation(), $this->flashMessageService, $this->frontendUrlService);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TYPO3_REQUEST']);
        GlobalVariableService::clearCache();

        parent::tearDown();
    }

    /**
     * @param array<string, mixed> $requestAttributes
     */
    private function setFrontendRequest(array $requestAttributes = []): void
    {
        $request = (new ServerRequest('http://example.org/', 'GET'))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('language', new SiteLanguage(0, 'en', new Uri('/'), ['title' => 'Default language']));

        foreach ($requestAttributes as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $GLOBALS['TYPO3_REQUEST'] = $request;
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function seedGlobalVariables(array $variables): void
    {
        (new ReflectionProperty(GlobalVariableService::class, 'cachedVariables'))
            ->setValue(null, [FrontendUserProvider::class => $variables]);
    }

    private function pageWithUid(int $uid): Page
    {
        $page = new Page();
        (new ReflectionProperty(AbstractDomainObject::class, 'uid'))->setValue($page, $uid);

        return $page;
    }

    #[Test]
    public function anInvalidModeIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1564068237);

        $this->validationService->requiresFrontendUser('FOO');
    }

    #[Test]
    public function aLoggedInFrontendUserPassesTheCheck(): void
    {
        $this->setFrontendRequest();
        $this->seedGlobalVariables(['user' => new FrontendUser()]);

        self::assertNull($this->validationService->requiresFrontendUser());
    }

    #[Test]
    public function theThrowExceptionModeThrowsARuntimeExceptionForGuests(): void
    {
        $this->setFrontendRequest();
        $this->seedGlobalVariables(['user' => null]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(1621981688);

        $this->validationService->requiresFrontendUser(ValidationService::MODES['THROW_EXCEPTION']);
    }

    /**
     * @throws AspectNotFoundException
     */
    #[Test]
    public function theRedirectModeRedirectsToTheConfiguredLoginPageForGuests(): void
    {
        $this->setFrontendRequest();
        $this->seedGlobalVariables(['user' => null, 'loginPage' => $this->pageWithUid(7)]);

        $this->frontendUrlService->method('buildPageUrl')
            ->with(7)
            ->willReturn('https://example.org/felogin');

        $flashMessageQueue = $this->createMock(FlashMessageQueue::class);
        $flashMessageQueue->expects(self::once())
            ->method('addMessage')
            ->with(
                self::callback(
                    static function (FlashMessage $message): bool {
                        return $message->getSeverity() === ContextualFeedbackSeverity::ERROR;
                    },
                ),
            );

        $this->flashMessageService->method('getMessageQueueByIdentifier')
            ->with('psbFoundation.general')
            ->willReturn($flashMessageQueue);

        try {
            $this->validationService->requiresFrontendUser();
            self::fail('An exception has not been thrown.');
        } catch (PropagateResponseException $exception) {
            self::assertSame(1621982088, $exception->getCode());

            $response = $exception->getResponse();

            self::assertInstanceOf(RedirectResponse::class, $response);
            self::assertSame(401, $response->getStatusCode());
            self::assertSame('https://example.org/felogin', $response->getHeaderLine('location'));
        }
    }

    /**
     * @throws AspectNotFoundException
     */
    #[Test]
    public function theRedirectModeFallsBackToTheRootPageWithoutALoginPage(): void
    {
        $pageInformation = new PageInformation();
        $pageInformation->setRootLine([['uid' => 3]]);

        $this->setFrontendRequest(['frontend.page.information' => $pageInformation]);
        $this->seedGlobalVariables(['user' => null, 'loginPage' => null]);

        $this->frontendUrlService->method('buildPageUrl')
            ->with(3)
            ->willReturn('https://example.org/');

        try {
            $this->validationService->requiresFrontendUser();
            self::fail('An exception has not been thrown.');
        } catch (PropagateResponseException $exception) {
            $response = $exception->getResponse();

            self::assertInstanceOf(RedirectResponse::class, $response);
            self::assertSame(401, $response->getStatusCode());
            self::assertSame('https://example.org/', $response->getHeaderLine('location'));
        }
    }
}
