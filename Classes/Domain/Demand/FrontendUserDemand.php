<?php

declare(strict_types=1);

/*
 * This file is part of PSBits Frontend Users.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace PSBits\FrontendUsers\Domain\Demand;

/**
 * Class FrontendUserDemand
 *
 * @package PSBits\FrontendUsers\Domain\Demand
 */
class FrontendUserDemand
{
    protected ?string $email  = null;
    protected ?int    $limit  = null;
    protected ?int    $offset = null;

    /** @var array<string, string> */
    protected array   $orderings = [];
    protected ?string $username  = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /** @return array<string, string> */
    public function getOrderings(): array
    {
        return $this->orderings;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /** @param array<string, string> $orderings */
    public function setOrderings(array $orderings): self
    {
        $this->orderings = $orderings;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
