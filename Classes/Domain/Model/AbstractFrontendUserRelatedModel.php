<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Model;

use PSBits\Foundation\Attribute\TCA\ColumnType\Select;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class AbstractFrontendUserRelatedModel
 *
 * By extending this class you can connect your model with a frontend user.
 *
 * @package PSBits\FrontendUsers\Domain\Model
 */
abstract class AbstractFrontendUserRelatedModel extends AbstractEntity
{
    #[Select(linkedModel: FrontendUser::class)]
    protected ?FrontendUser $frontendUser = null;

    public function getFrontendUser(): ?FrontendUser
    {
        return $this->frontendUser;
    }

    public function setFrontendUser(FrontendUser $frontendUser): void
    {
        $this->frontendUser = $frontendUser;
    }
}
