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
use PSB\PsbFrontendUsers\Traits\PropertyInjection\FrontendUserServiceTrait;
use PSB\PsbFoundation\Utility\ValidationUtility;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;

/**
 * Class FrontendUserProvider
 *
 * @package PSB\PsbFrontendUsers\Service\GlobalVariableProviders
 */
class FrontendUserProvider extends AbstractProvider
{
    use FrontendUserServiceTrait;

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
            'loginPage' => $this->frontendUserService->getLoginPage(),
            'sysFolder' => $this->frontendUserService->getSysFolder(),
            'user'      => $this->frontendUserService->getCurrentUser(),
        ];
    }
}
