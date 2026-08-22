<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\ViewHelpers\GlobalVariables;

use Closure;
use Exception;
use PSBits\Foundation\ViewHelpers\GlobalVariables\AbstractGlobalVariablesViewHelper;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class FrontendUserViewHelper
 *
 * @package PSBits\FrontendUsers\ViewHelpers\GlobalVariables
 */
class FrontendUserViewHelper extends AbstractGlobalVariablesViewHelper
{
    /**
     * @param array<string, mixed> $arguments
     * @param Closure(): mixed $renderChildrenClosure
     *
     * @throws Exception
     */
    public static function renderStatic(
        array                     $arguments,
        Closure                   $renderChildrenClosure,
        RenderingContextInterface $renderingContext,
    ): mixed {
        return parent::getVariable(FrontendUserProvider::class, $arguments);
    }
}
