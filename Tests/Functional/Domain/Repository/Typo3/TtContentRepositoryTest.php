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
use PSBits\FrontendUsers\Domain\Repository\Typo3\TtContentRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class TtContentRepositoryTest
 *
 * @package PSBits\FrontendUsers\Tests\Functional\Domain\Repository\Typo3
 */
class TtContentRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/psbits/foundation',
        'typo3conf/ext/psbits/frontend-users',
    ];

    private TtContentRepository $ttContentRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ttContentRepository = $this->get(TtContentRepository::class);
    }

    private function createTtContent(string $cType): int
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('tt_content');
        $row        = [
            'pid'   => 0,
            'CType' => $cType,
        ];

        $connection->insert('tt_content', $row);

        return (int)$connection->lastInsertId();
    }

    #[Test]
    public function contentWithAMatchingCTypeIsFound(): void
    {
        $this->createTtContent('felogin_login');
        $this->createTtContent('textmedia');

        $content = $this->ttContentRepository->findByCType('felogin_login');

        self::assertCount(1, $content);
        self::assertSame('felogin_login', $content->getFirst()->getCType());
    }

    #[Test]
    public function anUnknownCTypeYieldsAnEmptyResult(): void
    {
        $this->createTtContent('textmedia');

        $content = $this->ttContentRepository->findByCType('felogin_login');

        self::assertCount(0, $content);
    }
}
