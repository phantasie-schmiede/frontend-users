<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\ViewHelpers\GlobalVariables;

use PSBits\Foundation\ViewHelpers\GlobalVariables\AbstractGlobalVariablesViewHelper;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;

/**
 * Class FrontendUserViewHelper
 *
 * @package PSBits\FrontendUsers\ViewHelpers\GlobalVariables
 */
class FrontendUserViewHelper extends AbstractGlobalVariablesViewHelper
{
    protected function getBaseKey(): string
    {
        return FrontendUserProvider::class;
    }
}
