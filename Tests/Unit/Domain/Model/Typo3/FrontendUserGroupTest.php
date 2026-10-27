<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Domain\Model\Typo3;

use PHPUnit\Framework\Attributes\Test;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUserGroup;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class FrontendUserGroupTest
 *
 * @package PSBits\FrontendUsers\Tests\Unit\Domain\Model\Typo3
 */
class FrontendUserGroupTest extends UnitTestCase
{
    #[Test]
    public function theSubgroupsStartEmpty(): void
    {
        self::assertCount(0, (new FrontendUserGroup())->getSubgroup());
    }

    #[Test]
    public function subgroupsCanBeAttachedAndDetached(): void
    {
        $group    = new FrontendUserGroup();
        $subgroup = new FrontendUserGroup();

        $group->addSubgroup($subgroup);
        self::assertTrue($group->getSubgroup()->contains($subgroup));

        $group->removeSubgroup($subgroup);
        self::assertFalse($group->getSubgroup()->contains($subgroup));
    }
}
