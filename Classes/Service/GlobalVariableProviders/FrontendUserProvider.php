<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace PSB\PsbFrontendUsers\Service\GlobalVariableProviders;

use PSB\PsbFoundation\Service\GlobalVariableProviders\AbstractProvider;
use PSB\PsbFoundation\Utility\ValidationUtility;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\Page;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\TtContent;
use PSB\PsbFrontendUsers\Domain\Repository\Typo3\FrontendUserRepository;
use PSB\PsbFrontendUsers\Domain\Repository\Typo3\PageRepository;
use PSB\PsbFrontendUsers\Domain\Repository\Typo3\TtContentRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FrontendUserProvider
 *
 * @package PSB\PsbFrontendUsers\Service\GlobalVariableProviders
 */
class FrontendUserProvider extends AbstractProvider
{
    /**
     * @param FrontendUserRepository $frontendUserRepository
     * @param PageRepository         $pageRepository
     * @param TtContentRepository    $ttContentRepository
     */
    public function __construct(
        protected FrontendUserRepository       $frontendUserRepository,
        protected readonly PageRepository      $pageRepository,
        protected readonly TtContentRepository $ttContentRepository,
    ) {
    }

    /**
     * @return array
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     * @return FrontendUser|null
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     */
    private function getCurrentUser(): ?FrontendUser
    {
        return $this->frontendUserRepository->findByUid($this->getCurrentUserId());
    }

    /**
     * @return int
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     */
    private function getCurrentUserId(): int
    {
        $context = GeneralUtility::makeInstance(Context::class);

        return $context->getAspect('frontend.user')
            ->get('id');
    }

    /**
     * @return Page|null
     */
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

    /**
     * @return Page|null
     */
    private function getSysFolder(): ?Page
    {
        return $this->pageRepository->findByModule('fe_users')
            ->getFirst();
    }
}
