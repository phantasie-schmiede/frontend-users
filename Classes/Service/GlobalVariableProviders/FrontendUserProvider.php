<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Service\GlobalVariableProviders;

use PSBits\Foundation\Service\GlobalVariableProviders\AbstractProvider;
use PSBits\Foundation\Utility\ValidationUtility;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Domain\Model\Typo3\Page;
use PSBits\FrontendUsers\Domain\Model\Typo3\TtContent;
use PSBits\FrontendUsers\Domain\Repository\Typo3\FrontendUserRepository;
use PSBits\FrontendUsers\Domain\Repository\Typo3\PageRepository;
use PSBits\FrontendUsers\Domain\Repository\Typo3\TtContentRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FrontendUserProvider
 *
 * @package PSBits\FrontendUsers\Service\GlobalVariableProviders
 */
class FrontendUserProvider extends AbstractProvider
{
    public function __construct(
        protected readonly FrontendUserRepository $frontendUserRepository,
        protected readonly PageRepository         $pageRepository,
        protected readonly TtContentRepository    $ttContentRepository,
    ) {
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     */
    public function getGlobalVariables(): array
    {
        ValidationUtility::requiresFrontendContext();
        ValidationUtility::requiresTypoScriptLoaded();

        return [
            'loginPage' => $this->getLoginPage(),
            'sysFolder' => $this->getSysFolder(),
            'user'      => $this->getCurrentUser(),
        ];
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     */
    private function getCurrentUser(): ?FrontendUser
    {
        return $this->frontendUserRepository->findByUid($this->getCurrentUserId());
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     */
    private function getCurrentUserId(): int
    {
        $context = GeneralUtility::makeInstance(Context::class);

        return $context->getAspect('frontend.user')
            ->get('id');
    }

    private function getLoginPage(): ?Page
    {
        /** @var TtContent|null $plugin */
        $plugin = $this->ttContentRepository->findByCType('felogin_login')
            ->getFirst();

        if (null !== $plugin) {
            return $this->pageRepository->findByUid($plugin->getPid());
        }

        return null;
    }

    private function getSysFolder(): ?Page
    {
        return $this->pageRepository->findByModule('fe_users')
            ->getFirst();
    }
}
