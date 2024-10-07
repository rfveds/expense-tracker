<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\HasId;
use App\Entity\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'password_resets')]
#[HasLifecycleCallbacks]
class PasswordReset
{
    use HasTimestamps;
    use HasId;

    #[Column]
    private string $email;

    #[Column(unique: true)]
    private string $token;

    #[Column(name: 'is_active', options: ['default' => true])]
    private bool $isActive;

    #[Column]
    private \DateTime $expiration;

    public function __construct()
    {
        $this->isActive = true;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): PasswordReset
    {
        $this->email = $email;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): PasswordReset
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTime $expiration): PasswordReset
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): PasswordReset
    {
        $this->token = $token;

        return $this;
    }
}