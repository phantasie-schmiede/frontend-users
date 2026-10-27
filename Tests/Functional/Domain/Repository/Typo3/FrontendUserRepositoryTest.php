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
use PSBits\FrontendUsers\Domain\Demand\FrontendUserDemand;
use PSBits\FrontendUsers\Domain\Model\Typo3\FrontendUser;
use PSBits\FrontendUsers\Domain\Repository\Typo3\FrontendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Class FrontendUserRepositoryTest
 *
 * @package PSBits\FrontendUsers\Tests\Functional\Domain\Repository\Typo3
 */
class FrontendUserRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/psbits/foundation',
        'typo3conf/ext/psbits/frontend-users',
    ];

    private FrontendUserRepository $frontendUserRepository;
    private PersistenceManager     $persistenceManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->frontendUserRepository = $this->get(FrontendUserRepository::class);
        $this->persistenceManager     = $this->get(PersistenceManager::class);
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    private function createUsers(): void
    {
        $john = new FrontendUser();
        $john->setUsername('john.doe');
        $john->setEmail('john.doe@example.org');
        $john->setFirstName('John');
        $john->setLastName('Doe');

        $jane = new FrontendUser();
        $jane->setUsername('jane.doe');
        $jane->setEmail('jane.doe@example.org');

        $jack = new FrontendUser();
        $jack->setUsername('jack.doe');
        $jack->setEmail('jack.doe@example.org');

        $this->frontendUserRepository->add($john);
        $this->frontendUserRepository->add($jane);
        $this->frontendUserRepository->add($jack);

        $this->persistenceManager->persistAll();
        $this->persistenceManager->clearState();
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    #[Test]
    public function aDemandWithAUsernameMatchesOnlyThatUser(): void
    {
        $this->createUsers();

        $demand = (new FrontendUserDemand())->setUsername('john.doe');
        $user   = $this->frontendUserRepository->findOneByDemand($demand);

        self::assertNotNull($user);
        self::assertSame('john.doe', $user->getUsername());
        self::assertSame('John', $user->getFirstName());
        self::assertSame('Doe', $user->getLastName());
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    #[Test]
    public function aDemandWithAnEmailMatchesOnlyThatUser(): void
    {
        $this->createUsers();

        $demand = (new FrontendUserDemand())->setEmail('jane.doe@example.org');
        $users  = $this->frontendUserRepository->findByDemand($demand);

        self::assertCount(1, $users);
        self::assertSame('jane.doe', $users->getFirst()->getUsername());
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    #[Test]
    public function aDemandWithALimitAndOffsetSkipsAndLimitsTheResults(): void
    {
        $this->createUsers();

        $demand = (new FrontendUserDemand())
            ->setLimit(1)
            ->setOffset(1)
            ->setOrderings(['username' => QueryInterface::ORDER_ASCENDING]);
        $users = $this->frontendUserRepository->findByDemand($demand);

        self::assertCount(1, $users);
        self::assertSame('jane.doe', $users->getFirst()->getUsername());
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    #[Test]
    public function aDemandWithoutConstraintsMatchesAllUsers(): void
    {
        $this->createUsers();

        $users = $this->frontendUserRepository->findByDemand(new FrontendUserDemand());

        self::assertCount(3, $users);
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws NotImplementedException
     */
    #[Test]
    public function aDemandWithoutAMatchYieldsNull(): void
    {
        $this->createUsers();

        $demand = (new FrontendUserDemand())->setUsername('nobody');

        self::assertNull($this->frontendUserRepository->findOneByDemand($demand));
    }
}
