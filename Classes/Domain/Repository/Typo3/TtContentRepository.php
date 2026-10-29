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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
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
    public function __construct()
    {
        parent::__construct();

        // Always search the whole page tree (ignore storage page).
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /** @return QueryResultInterface<int, TtContent> */
    public function findByCType(string $cType): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('cType', $cType));

        return $query->execute();
    }
}
