<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Functional\Service\GlobalVariableProviders;

use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\Test;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Domain\Repository\Typo3\FrontendUserRepository;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use PSBits\Qa\Testing\Fixtures\FrontendRequestFactory;
use PSBits\Qa\Testing\Support\PersistenceSupportTrait;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class FrontendUserProviderTest
 *
 * The logged in frontend user is simulated through the frontend.user context aspect, the remaining variables are
 * served from the database.
 *
 * @package PSBits\FrontendUsers\Tests\Functional\Service\GlobalVariableProviders
 */
#[IgnoreDeprecations]
class FrontendUserProviderTest extends FunctionalTestCase
{
    use PersistenceSupportTrait;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/psbits/foundation',
        'typo3conf/ext/psbits/frontend-users',
    ];

    protected function tearDown(): void
    {
        unset($GLOBALS['TYPO3_REQUEST']);

        parent::tearDown();
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    private function createFrontendUser(): FrontendUser
    {
        $user = new FrontendUser();
        $user->setUsername('john.doe');

        $this->get(FrontendUserRepository::class)->add($user);
        $this->persistAll();

        return $user;
    }

    private function createPage(string $module = ''): int
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('pages');
        $connection->insert('pages', [
            'pid'         => 0,
            'title'       => 'Test page',
            'module'      => $module,
            'is_siteroot' => 0,
            'deleted'     => 0,
        ]);

        return (int)$connection->lastInsertId();
    }

    private function createFeloginPlugin(int $pageId): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('tt_content');
        $connection->insert('tt_content', [
            'pid'   => $pageId,
            'CType' => 'felogin_login',
        ]);
    }

    private function simulateLoggedInFrontendUser(int $uid): void
    {
        $authentication = new FrontendUserAuthentication();
        $authentication->user          = ['uid' => $uid, 'username' => 'john.doe'];
        $authentication->userid_column = 'uid';

        GeneralUtility::makeInstance(Context::class)
            ->setAspect('frontend.user', new UserAspect($authentication));
    }

    #[Test]
    public function theGlobalVariablesAreProvidedForALoggedInUser(): void
    {
        $sysFolderPageUid = $this->createPage('fe_users');
        $loginPageUid     = $this->createPage();
        $this->createFeloginPlugin($loginPageUid);

        $user = $this->createFrontendUser();
        $this->simulateLoggedInFrontendUser((int)$user->getUid());
        $GLOBALS['TYPO3_REQUEST'] = FrontendRequestFactory::createFrontendRequest('tx_frontendusers');

        $variables = $this->get(FrontendUserProvider::class)->getGlobalVariables();

        self::assertNotNull($variables['user']);
        self::assertSame($user->getUid(), $variables['user']->getUid());
        self::assertNotNull($variables['loginPage']);
        self::assertSame($loginPageUid, $variables['loginPage']->getUid());
        self::assertNotNull($variables['sysFolder']);
        self::assertSame($sysFolderPageUid, $variables['sysFolder']->getUid());
    }

    #[Test]
    public function theUserIsNullForAGuest(): void
    {
        $this->createFrontendUser();

        GeneralUtility::makeInstance(Context::class)
            ->setAspect('frontend.user', new UserAspect(null, [0, -1]));
        $GLOBALS['TYPO3_REQUEST'] = FrontendRequestFactory::createFrontendRequest('tx_frontendusers');

        $variables = $this->get(FrontendUserProvider::class)->getGlobalVariables();

        self::assertNull($variables['user']);
    }
}
