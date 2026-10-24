<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Data;

use PHPUnit\Framework\TestCase;
use PSBits\FrontendUsers\Data\ExtensionInformation;

final class ExtensionInformationTest extends TestCase
{
    public function testRegistersNoModulesAndPlugins(): void
    {
        $extensionInformation = new ExtensionInformation();

        self::assertSame([], $extensionInformation->getMainModules());
        self::assertSame([], $extensionInformation->getModules());
        self::assertSame([], $extensionInformation->getPlugins());
        self::assertSame([], $extensionInformation->getPageTypes());
    }
}
