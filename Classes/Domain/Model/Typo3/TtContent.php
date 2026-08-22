<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Model\Typo3;

use PSBits\Foundation\Attribute\TCA\Mapping\Field;
use PSBits\Foundation\Attribute\TCA\Mapping\Table;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class TtContent
 *
 * @package PSBits\FrontendUsers\Domain\Model\Typo3
 */
#[Table(name: 'tt_content')]
class TtContent extends AbstractEntity
{
    #[Field(name: 'CType')]
    protected string $cType    = '';
    protected string $listType = '';

    public function getCType(): string
    {
        return $this->cType;
    }

    public function getListType(): string
    {
        return $this->listType;
    }
}
