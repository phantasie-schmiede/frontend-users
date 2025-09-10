<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Model\Typo3;

use PSBits\Foundation\Attribute\TCA\Mapping\Table;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Page
 *
 * @package PSBits\FrontendUsers\Domain\Model\Typo3
 */
#[Table(name: 'pages')]
class Page extends AbstractEntity
{
    protected string $module = '';

    public function getModule(): string
    {
        return $this->module;
    }
}
