<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Service;

use Exception;
use JsonException;
use PSBits\Foundation\Service\GlobalVariableService;
use PSBits\Foundation\Utility\Configuration\FilePathUtility;
use PSBits\Foundation\Utility\LocalizationUtility;
use PSBits\Foundation\Utility\ValidationUtility;
use PSBits\FrontendUsers\Data\ExtensionInformation;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Class ObjectService
 *
 * @package PSBits\FrontendUsers\Service
 */
readonly class ValidationService
{
    public const array MODES = [
        'REDIRECT'        => 'redirect',
        'THROW_EXCEPTION' => 'throwException',
    ];

    public function __construct(
        protected ExtensionInformation $extensionInformation,
        protected FlashMessageService  $flashMessageService,
        protected UriBuilder           $uriBuilder,
    ) {
    }

    /**
     * @throws AspectNotFoundException
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws PropagateResponseException
     */
    public function requiresFrontendUser(string $mode = self::MODES['REDIRECT']): void
    {
        ValidationUtility::checkValueAgainstConstant(self::MODES, $mode);
        ValidationUtility::requiresFrontendContext();

        if (null === GlobalVariableService::get(FrontendUserProvider::class . '.user')) {
            switch ($mode) {
                case self::MODES['REDIRECT']:
                    $this->addFlashMessage();
                    throw new PropagateResponseException(
                        new RedirectResponse($this->buildRedirectUri(), 401), 1621982088
                    );
                case self::MODES['THROW_EXCEPTION']:
                    throw new RuntimeException(
                        __CLASS__ . ': This method requires a logged in frontend user!', 1621981688
                    );
            }
        }
    }

    /**
     * @throws AspectNotFoundException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    private function addFlashMessage(): void
    {
        $languageFilePath = FilePathUtility::getLanguageFilePathForCurrentFile($this->extensionInformation);
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            LocalizationUtility::translate($languageFilePath . 'flashMessage.redirect.bodytext'),
            LocalizationUtility::translate($languageFilePath . 'flashMessage.redirect.header'),
            ContextualFeedbackSeverity::ERROR,
            true
        );
        $this->flashMessageService->getMessageQueueByIdentifier('psbFoundation.general')
            ->addMessage($message);
    }

    /**
     * @throws Exception
     */
    private function buildRedirectUri(): string
    {
        $loginPage = GlobalVariableService::get(FrontendUserProvider::class . '.loginPage');

        if (null === $loginPage) {
            $targetPageUid = $GLOBALS['TSFE']->rootLine[0]['uid'];
        } else {
            $targetPageUid = $loginPage->getUid();
        }

        return $this->uriBuilder->reset()
            ->setTargetPageUid($targetPageUid)
            ->buildFrontendUri();
    }
}
