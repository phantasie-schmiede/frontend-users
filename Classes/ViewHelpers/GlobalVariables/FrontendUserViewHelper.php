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

namespace PSB\PsbFrontendUsers\ViewHelpers\GlobalVariables;

use Closure;
use PSB\PsbFoundation\ViewHelpers\GlobalVariables\AbstractGlobalVariablesViewHelper;
use PSB\PsbFrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class FrontendUserViewHelper
 *
 * @package PSB\PsbFrontendUsers\ViewHelpers\GlobalVariables
 */
class FrontendUserViewHelper extends AbstractGlobalVariablesViewHelper
{
    /**
     * @param array                     $arguments
     * @param Closure                   $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): mixed {
        return parent::getVariable(FrontendUserProvider::class, $arguments);
    }
}
