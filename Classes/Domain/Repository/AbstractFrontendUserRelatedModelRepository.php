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

namespace PSB\PsbFrontendUsers\Domain\Repository;

use PSB\PsbFrontendUsers\Domain\Model\AbstractFrontendUserRelatedModel;
use PSB\PsbFrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSB\PsbFrontendUsers\Traits\PropertyInjection\FrontendUserServiceTrait;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class AbstractFrontendUserRelatedModelRepository
 *
 * This repository lets you find records that are connected to a certain frontend user. Goes hand in hand with a model
 * extending \PSB\PsbFrontendUsers\Domain\Model\AbstractFrontendUserRelatedModel
 *
 * @package PSB\PsbFrontendUsers\Domain\Repository
 */
abstract class AbstractFrontendUserRelatedModelRepository extends Repository
{
    use FrontendUserServiceTrait;

    /**
     * @param object $object
     *
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws IllegalObjectTypeException
     */
    public function add($object): void
    {
        if ($object instanceof AbstractFrontendUserRelatedModel
            && null === $object->getFrontendUser()
        ) {
            $object->setFrontendUser($this->frontendUserService->getCurrentUser());
        }

        parent::add($object);
    }

    /**
     * @param FrontendUser|null $frontendUser
     *
     * @return QueryResultInterface
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     */
    public function findByFrontendUser(FrontendUser $frontendUser = null): QueryResultInterface
    {
        if (null === $frontendUser) {
            $frontendUser = $this->frontendUserService->getCurrentUser();
        }

        /** @var Query $query */
        $query = $this->createQuery();
        $query->matching($query->equals('frontendUser', $frontendUser));

        return $query->execute();
    }
}
