<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\HasId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('user_login_codes')]
class UserLoginCode
{
    use  HasId;

    #[Column(length: 6)]
    private string $code;

    #[Column(name: 'is_active', options: ['default' => true])]
    private bool $isActive;

    #[Column]
    private \DateTime $expiration;

    #[ManyToOne]
    private User $user;

    public function __construct()
    {
        $this->isActive = true;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): UserLoginCode
    {
        $this->code = $code;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): UserLoginCode
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTime $expiration): UserLoginCode
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserLoginCode
    {
        $this->user = $user;

        return $this;
    }
}