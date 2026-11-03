<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\ViewHelpers\GlobalVariables;

use PHPUnit\Framework\Attributes\Test;
use PSBits\Foundation\Service\GlobalVariableService;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use PSBits\FrontendUsers\ViewHelpers\GlobalVariables\FrontendUserViewHelper;
use ReflectionProperty;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class FrontendUserViewHelperTest
 *
 * The provider variables are served from the pre-seeded global variable cache.
 *
 * @package PSBits\FrontendUsers\Tests\Unit\ViewHelpers\GlobalVariables
 */
class FrontendUserViewHelperTest extends UnitTestCase
{
    private FrontendUser $frontendUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->frontendUser = new FrontendUser();
        $this->frontendUser->setUsername('john.doe');

        (new ReflectionProperty(GlobalVariableService::class, 'cachedVariables'))
            ->setValue(
                null,
                [
                    FrontendUserProvider::class => [
                        'loginPage' => null,
                        'sysFolder' => null,
                        'user'      => $this->frontendUser,
                    ],
                ],
            );
    }

    protected function tearDown(): void
    {
        GlobalVariableService::clearCache();

        parent::tearDown();
    }

    private function createViewHelper(): FrontendUserViewHelper
    {
        $viewHelper = new FrontendUserViewHelper();
        $viewHelper->initializeArguments();

        return $viewHelper;
    }

    #[Test]
    public function thePathArgumentIsOptional(): void
    {
        $definitions = $this->createViewHelper()
            ->prepareArguments();

        self::assertArrayHasKey('path', $definitions);
        self::assertFalse($definitions['path']->isRequired());
    }

    #[Test]
    public function renderingWithoutAPathReturnsAllProviderVariables(): void
    {
        $viewHelper = $this->createViewHelper();
        $viewHelper->setArguments(['path' => '', 'strict' => true, 'fallback' => null]);

        self::assertSame(
            [
                'loginPage' => null,
                'sysFolder' => null,
                'user'      => $this->frontendUser,
            ],
            $viewHelper->render(),
        );
    }

    #[Test]
    public function renderingWithAPathReturnsTheSubVariable(): void
    {
        $viewHelper = $this->createViewHelper();
        $viewHelper->setArguments(['path' => 'user.username', 'strict' => true, 'fallback' => null]);

        self::assertSame('john.doe', $viewHelper->render());
    }
}
