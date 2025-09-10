<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Repository\Typo3;

use PSBits\FrontendUsers\Domain\Model\Typo3\Page;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class PageRepository
 *
 * @extends Repository<Page>
 * @package PSBits\FrontendUsers\Domain\Repository\Typo3
 */
class PageRepository extends Repository
{
    public function findByModule(string $module): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('module', $module));

        return $query->execute();
    }

    public function initializeObject(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
