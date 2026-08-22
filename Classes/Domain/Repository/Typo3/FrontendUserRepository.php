<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Repository\Typo3;

use PSBits\FrontendUsers\Domain\Demand\FrontendUserDemand;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class FrontendUserRepository
 *
 * @extends Repository<FrontendUser>
 * @package PSBits\FrontendUsers\Domain\Repository\Typo3
 */
class FrontendUserRepository extends Repository
{
    public function findByDemand(FrontendUserDemand $demand): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if (null !== $demand->getUid()) {
            $constraints[] = $query->equals('uid', $demand->getUid());
        }

        if (null !== $demand->getUsername()) {
            $constraints[] = $query->equals('username', $demand->getUsername());
        }

        if (null !== $demand->getEmail()) {
            $constraints[] = $query->equals('email', $demand->getEmail());
        }

        if ([] !== $constraints) {
            $query->matching(1 === count($constraints) ? $constraints[0] : $query->logicalAnd(...$constraints));
        }

        if (null !== $demand->getLimit()) {
            $query->setLimit($demand->getLimit());
        }

        if (null !== $demand->getOffset()) {
            $query->setOffset($demand->getOffset());
        }

        if ([] !== $demand->getOrderings()) {
            $query->setOrderings($demand->getOrderings());
        }

        return $query->execute();
    }

    public function findOneByDemand(FrontendUserDemand $demand): ?FrontendUser
    {
        return $this->findByDemand($demand)
            ->getFirst();
    }
}
