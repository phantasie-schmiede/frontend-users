<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Functional\Domain\Repository\Typo3;

use PHPUnit\Framework\Attributes\Test;
use PSBits\FrontendUsers\Domain\Repository\Typo3\PageRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class PageRepositoryTest
 *
 * @package PSBits\FrontendUsers\Tests\Functional\Domain\Repository\Typo3
 */
class PageRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/psbits/foundation',
        'typo3conf/ext/psbits/frontend-users',
    ];

    private PageRepository $pageRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pageRepository = $this->get(PageRepository::class);
    }

    private function createPage(string $module): int
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('pages');
        $connection->insert('pages', [
            'pid'         => 0,
            'title'       => 'Test page',
            'module'      => $module,
            'is_siteroot' => 0,
            'deleted'     => 0,
        ]);

        return (int)$connection->lastInsertId();
    }

    #[Test]
    public function pagesWithAMatchingModuleAreFound(): void
    {
        $this->createPage('fe_users');
        $this->createPage('be_users');

        $pages = $this->pageRepository->findByModule('fe_users');

        self::assertCount(1, $pages);
        self::assertSame('fe_users', $pages->getFirst()->getModule());
    }

    #[Test]
    public function aModuleWithoutMatchingPagesYieldsAnEmptyResult(): void
    {
        $this->createPage('be_users');

        $pages = $this->pageRepository->findByModule('fe_users');

        self::assertCount(0, $pages);
    }
}
