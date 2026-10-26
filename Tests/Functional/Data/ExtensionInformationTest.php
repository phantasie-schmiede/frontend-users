<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Functional\Data;

use PHPUnit\Framework\Attributes\Test;
use PSBits\FrontendUsers\Data\ExtensionInformation;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class ExtensionInformationTest
 *
 * Verifies the extension information within a booted test instance, so it
 * runs as a functional test.
 *
 * @package PSBits\FrontendUsers\Tests\Functional\Data
 */
class ExtensionInformationTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/psbits/foundation',
        'typo3conf/ext/psbits/frontend-users',
    ];

    #[Test]
    public function theInformationIsExtractedFromTheExtensionNamespace(): void
    {
        $information = new ExtensionInformation();

        self::assertSame('PSBits', $information->getVendorName());
        self::assertSame('FrontendUsers', $information->getExtensionName());
        self::assertSame('frontend_users', $information->getExtensionKey());
    }

    #[Test]
    public function testRegistersNoModulesAndPlugins(): void
    {
        $information = new ExtensionInformation();

        self::assertSame([], $information->getMainModules());
        self::assertSame([], $information->getModules());
        self::assertSame([], $information->getPageTypes());
        self::assertSame([], $information->getPlugins());
    }
}
