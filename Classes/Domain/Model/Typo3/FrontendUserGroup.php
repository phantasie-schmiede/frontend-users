<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Model\Typo3;

use PSBits\Foundation\Attribute\TCA\Mapping\Table;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class FrontendUserGroup
 *
 * @package PSBits\FrontendUsers\Domain\Model\Typo3
 */
#[Table(name: 'fe_groups')]
class FrontendUserGroup extends AbstractEntity
{
    protected string $description = '';

    /**
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected ObjectStorage $subgroup;

    protected string $title = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addSubgroup(FrontendUserGroup $subgroup): self
    {
        $this->subgroup->attach($subgroup);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the subgroups. Keep in mind that the property is called "subgroup"
     * although it can hold several subgroups.
     *
     * @return ObjectStorage<FrontendUserGroup> An object storage containing the subgroups
     */
    public function getSubgroup(): ObjectStorage
    {
        return $this->subgroup;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function initializeObject(): void
    {
        $this->subgroup = new ObjectStorage();
    }

    public function removeSubgroup(FrontendUserGroup $subgroup): self
    {
        $this->subgroup->detach($subgroup);

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the subgroups. Keep in mind that the property is called "subgroup"
     * although it can hold several subgroups.
     *
     * @param ObjectStorage<FrontendUserGroup> $subgroup An object storage containing the subgroups to add
     */
    public function setSubgroup(ObjectStorage $subgroup): self
    {
        $this->subgroup = $subgroup;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
