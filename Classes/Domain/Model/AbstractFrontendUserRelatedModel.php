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

namespace PSB\PsbFrontendUsers\Domain\Model;

use PSB\PsbFoundation\Annotation\TCA\Column\Select;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\FrontendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class AbstractFrontendUserRelatedModel
 *
 * By extending this class you can connect your model with a frontend user.
 *
 * @package PSB\PsbFrontendUsers\Domain\Model
 */
abstract class AbstractFrontendUserRelatedModel extends AbstractEntity
{
    /**
     * @var FrontendUser|null
     * @Select(linkedModel=FrontendUser::class)
     */
    protected ?FrontendUser $frontendUser = null;

    /**
     * @return FrontendUser|null
     */
    public function getFrontendUser(): ?FrontendUser
    {
        return $this->frontendUser;
    }

    /**
     * @param FrontendUser $frontendUser
     */
    public function setFrontendUser(FrontendUser $frontendUser): void
    {
        $this->frontendUser = $frontendUser;
    }
}
