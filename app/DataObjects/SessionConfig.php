<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enum\SameSite;

readonly class SessionConfig
{
    public function __construct(
        public string $name,
        public string $flashName,
        public bool $secure,
        public bool $httpOnly,
        public SameSite $sameSite
    ) {
    }
}