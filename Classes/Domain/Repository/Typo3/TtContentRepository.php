<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Repository\Typo3;

use PSBits\FrontendUsers\Domain\Model\Typo3\TtContent;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class TtContentRepository
 *
 * @extends Repository<TtContent>
 * @package PSBits\FrontendUsers\Domain\Repository\Typo3
 */
class TtContentRepository extends Repository
{
    public function findByCType(string $cType): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('cType', $cType));

        return $query->execute();
    }

    public function findByListType(string $listType): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('listType', $listType));

        return $query->execute();
    }

    /**
     * Always search the whole page tree (ignore storage page).
     */
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()
            ->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
