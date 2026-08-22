<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Repository;

use Exception;
use PSBits\Foundation\Service\GlobalVariableService;
use PSBits\FrontendUsers\Domain\Model\AbstractFrontendUserRelatedModel;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class AbstractFrontendUserRelatedModelRepository
 *
 * This repository lets you find records that are connected to a certain frontend user. Goes hand in hand with a model
 * extending \PSBits\FrontendUsers\Domain\Model\AbstractFrontendUserRelatedModel
 *
 * @template TModel of AbstractFrontendUserRelatedModel
 * @extends Repository<TModel>
 * @package PSBits\FrontendUsers\Domain\Repository
 */
abstract class AbstractFrontendUserRelatedModelRepository extends Repository
{
    /**
     * @param TModel $object
     *
     * @throws Exception
     * @throws IllegalObjectTypeException
     */
    public function add($object): void
    {
        if (null === $object->getFrontendUser()) {
            /** @var FrontendUser|null $frontendUser */
            $frontendUser = GlobalVariableService::get(FrontendUserProvider::class . '.user');
            $object->setFrontendUser($frontendUser);
        }

        parent::add($object);
    }

    /**
     * @return QueryResultInterface<int, TModel>
     * @throws Exception
     */
    public function findByFrontendUser(?FrontendUser $frontendUser = null): QueryResultInterface
    {
        if (null === $frontendUser) {
            /** @var FrontendUser|null $frontendUser */
            $frontendUser = GlobalVariableService::get(FrontendUserProvider::class . '.user');
        }

        /** @var Query<TModel> $query */
        $query = $this->createQuery();
        $query->matching($query->equals('frontendUser', $frontendUser));

        return $query->execute();
    }
}
