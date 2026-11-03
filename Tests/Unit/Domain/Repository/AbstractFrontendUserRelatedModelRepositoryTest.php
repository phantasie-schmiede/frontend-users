<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\Attributes\Test;
use PSBits\Foundation\Service\GlobalVariableService;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Service\GlobalVariableProviders\FrontendUserProvider;
use PSBits\FrontendUsers\Tests\Unit\Fixtures\TestFrontendUserRelatedModel;
use PSBits\FrontendUsers\Tests\Unit\Fixtures\TestFrontendUserRelatedModelRepository;
use ReflectionProperty;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class AbstractFrontendUserRelatedModelRepositoryTest
 *
 * @package PSBits\FrontendUsers\Tests\Unit\Domain\Repository
 */
class AbstractFrontendUserRelatedModelRepositoryTest extends UnitTestCase
{
    protected function tearDown(): void
    {
        GlobalVariableService::clearCache();

        parent::tearDown();
    }

    private function seedCurrentFrontendUser(FrontendUser $frontendUser): void
    {
        (new ReflectionProperty(GlobalVariableService::class, 'cachedVariables'))
            ->setValue(null, [FrontendUserProvider::class => ['user' => $frontendUser]]);
    }

    private function createRepository(): TestFrontendUserRelatedModelRepository
    {
        $repository = new TestFrontendUserRelatedModelRepository();
        $repository->injectPersistenceManager($this->createMock(PersistenceManagerInterface::class));

        return $repository;
    }

    #[Test]
    public function theCurrentFrontendUserIsAssignedToAnObjectWithoutAUserOnAdd(): void
    {
        $currentFrontendUser = new FrontendUser();
        $this->seedCurrentFrontendUser($currentFrontendUser);

        $object     = new TestFrontendUserRelatedModel();
        $repository = $this->createRepository();

        $repository->add($object);

        self::assertSame($currentFrontendUser, $object->getFrontendUser());
    }

    #[Test]
    public function anAlreadyAssignedFrontendUserIsKeptOnAdd(): void
    {
        $currentFrontendUser = new FrontendUser();
        $assignedFrontendUser = new FrontendUser();
        $this->seedCurrentFrontendUser($currentFrontendUser);

        $object     = new TestFrontendUserRelatedModel();
        $object->setFrontendUser($assignedFrontendUser);
        $repository = $this->createRepository();

        $repository->add($object);

        self::assertSame($assignedFrontendUser, $object->getFrontendUser());
    }
}
