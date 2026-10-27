<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Domain\Demand;

use PHPUnit\Framework\Attributes\Test;
use PSBits\FrontendUsers\Domain\Demand\FrontendUserDemand;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class FrontendUserDemandTest
 *
 * @package PSBits\FrontendUsers\Tests\Unit\Domain\Demand
 */
class FrontendUserDemandTest extends UnitTestCase
{
    #[Test]
    public function theStoragePageIsRespectedByDefault(): void
    {
        self::assertTrue((new FrontendUserDemand())->isRespectStoragePage());
    }

    #[Test]
    public function allValuesDefaultToNull(): void
    {
        $demand = new FrontendUserDemand();

        self::assertNull($demand->getEmail());
        self::assertNull($demand->getLimit());
        self::assertNull($demand->getOffset());
        self::assertSame([], $demand->getOrderings());
        self::assertNull($demand->getUsername());
    }

    #[Test]
    public function theValuesCanBeStoredAndReadBack(): void
    {
        $demand = (new FrontendUserDemand())
            ->setEmail('john.doe@example.org')
            ->setLimit(10)
            ->setOffset(5)
            ->setOrderings(['username' => 'desc'])
            ->setRespectStoragePage(false)
            ->setUsername('john.doe');

        self::assertSame('john.doe@example.org', $demand->getEmail());
        self::assertSame(10, $demand->getLimit());
        self::assertSame(5, $demand->getOffset());
        self::assertSame(['username' => 'desc'], $demand->getOrderings());
        self::assertFalse($demand->isRespectStoragePage());
        self::assertSame('john.doe', $demand->getUsername());
    }
}
