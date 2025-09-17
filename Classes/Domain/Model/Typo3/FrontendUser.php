<?php
declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Model\Typo3;

use DateTime;
use JsonException;
use PSBits\Foundation\Attribute\TCA\Column;
use PSBits\Foundation\Attribute\TCA\ColumnType\Text;
use PSBits\Foundation\Attribute\TCA\Mapping\Field;
use PSBits\Foundation\Attribute\TCA\Mapping\Table;
use PSBits\Foundation\Utility\ArrayUtility;
use PSBits\Foundation\Utility\VariableUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class FrontendUser
 *
 * @package PSBits\FrontendUsers\Domain\Model\Typo3
 */
#[Table(name: 'fe_users')]
class FrontendUser extends AbstractEntity
{
    protected string $address   = '';
    protected string $city      = '';
    protected string $company   = '';
    protected string $country   = '';
    protected string $email     = '';
    protected string $fax       = '';
    protected string $firstName = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    protected ObjectStorage $image;

    #[Field(name: 'lastlogin')]
    protected ?DateTime $lastLogin  = null;
    protected string    $lastName   = '';
    protected string    $middleName = '';
    protected string    $name       = '';
    protected string    $password   = '';

    /** @var string $settings DataMapper needs this! */
    #[Column(exclude: true)]
    #[Text]
    protected string $settings  = '';
    protected string $telephone = '';
    protected string $title     = '';

    /**
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected ObjectStorage $usergroup;

    protected string $username = '';
    protected string $www      = '';
    protected string $zip      = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addUsergroup(FrontendUserGroup $usergroup): self
    {
        $this->usergroup->attach($usergroup);

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getFullName(): string
    {
        if (!empty($this->name)) {
            return $this->name;
        }

        if (empty($this->firstName) && empty($this->lastName)) {
            return $this->username;
        }

        if (!empty($this->firstName) && !empty($this->lastName)) {
            $spacer = ' ';
        }

        return $this->firstName . ($spacer ?? '') . $this->lastName;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImage(): ObjectStorage
    {
        return $this->image;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @throws JsonException
     */
    public function getSetting(string $key): mixed
    {
        return VariableUtility::getValueByPath($this->getSettings(), $key, false);
    }

    /**
     * @throws JsonException
     */
    public function getSettings(): array
    {
        return json_decode($this->settings ?: '[]', true, 512, JSON_THROW_ON_ERROR);
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return ObjectStorage<FrontendUserGroup>
     */
    public function getUsergroup(): ObjectStorage
    {
        return $this->usergroup;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function initializeObject(): void
    {
        $this->image = new ObjectStorage();
        $this->usergroup = new ObjectStorage();
    }

    public function removeUsergroup(FrontendUserGroup $usergroup): self
    {
        $this->usergroup->detach($usergroup);

        return $this;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setLastLogin(?DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function setSetting(string $key, mixed $value): void
    {
        $settings = $this->getSettings();
        ArrayUtility::setValueByPath($settings, $key, $value);

        $this->setSettings($settings);
    }

    /**
     * @throws JsonException
     */
    public function setSettings(array $settings): self
    {
        $this->settings = json_encode($settings, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @param ObjectStorage<FrontendUserGroup> $usergroup
     */
    public function setUsergroup(ObjectStorage $usergroup): self
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setWww(string $www): self
    {
        $this->www = $www;

        return $this;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }
}
