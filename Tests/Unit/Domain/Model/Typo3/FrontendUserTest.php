<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Domain\Model\Typo3;

use JsonException;
use PHPUnit\Framework\Attributes\Test;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUserGroup;
use ReflectionProperty;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class FrontendUserTest
 *
 * @package PSBits\FrontendUsers\Tests\Unit\Domain\Model\Typo3
 */
class FrontendUserTest extends UnitTestCase
{
    #[Test]
    public function theFullNamePrefersTheNameProperty(): void
    {
        $user = (new FrontendUser())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setName('Johnny D');

        self::assertSame('Johnny D', $user->getFullName());
    }

    #[Test]
    public function theFullNameJoinsTheFirstNameAndLastNameWithASpace(): void
    {
        $user = (new FrontendUser())
            ->setFirstName('John')
            ->setLastName('Doe');

        self::assertSame('John Doe', $user->getFullName());
    }

    #[Test]
    public function theFullNameReturnsTheFirstNameWithoutASpaceIfTheLastNameIsEmpty(): void
    {
        $user = (new FrontendUser())->setFirstName('John');

        self::assertSame('John', $user->getFullName());
    }

    #[Test]
    public function theFullNameReturnsTheLastNameWithoutASpaceIfTheFirstNameIsEmpty(): void
    {
        $user = (new FrontendUser())->setLastName('Doe');

        self::assertSame('Doe', $user->getFullName());
    }

    #[Test]
    public function theFullNameFallsBackToTheUsernameWithoutAnyNameParts(): void
    {
        $user = (new FrontendUser())->setUsername('john.doe');

        self::assertSame('john.doe', $user->getFullName());
    }

    #[Test]
    public function theSettingsDefaultToAnEmptyArray(): void
    {
        self::assertSame([], (new FrontendUser())->getSettings());
    }

    #[Test]
    public function theSettingsSurviveAJsonRoundTrip(): void
    {
        $user = (new FrontendUser())->setSettings(['fe_login' => ['username' => 'john.doe']]);

        self::assertSame(['fe_login' => ['username' => 'john.doe']], $user->getSettings());
    }

    #[Test]
    public function aSettingCanBeStoredAndReadByPath(): void
    {
        $user = new FrontendUser();

        $user->setSetting('fe_login.username', 'john.doe');

        self::assertSame('john.doe', $user->getSetting('fe_login.username'));
        self::assertSame(['fe_login' => ['username' => 'john.doe']], $user->getSettings());
    }

    #[Test]
    public function aMissingSettingReturnsNull(): void
    {
        self::assertNull((new FrontendUser())->getSetting('fe_login.username'));
    }

    #[Test]
    public function corruptedSettingsThrowAJsonException(): void
    {
        $user = new FrontendUser();
        (new ReflectionProperty(FrontendUser::class, 'settings'))->setValue($user, '{invalid');

        $this->expectException(JsonException::class);

        $user->getSettings();
    }

    #[Test]
    public function usergroupsCanBeAttachedAndDetached(): void
    {
        $user  = new FrontendUser();
        $group = new FrontendUserGroup();

        $user->addUsergroup($group);
        self::assertTrue($user->getUsergroup()->contains($group));

        $user->removeUsergroup($group);
        self::assertFalse($user->getUsergroup()->contains($group));
    }
}
