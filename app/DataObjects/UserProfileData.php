<?php

declare(strict_types=1);

namespace App\DataObjects;

readonly class UserProfileData
{
    public function __construct(
        public string $email,
        public string $name,
        public bool $twoFactor
    ) {
    }
}