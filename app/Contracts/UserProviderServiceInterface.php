<?php

declare(strict_types=1);

namespace App\Contracts;

interface UserProviderServiceInterface
{
    public function findById(int $id): ?UserInterface;

    public function findByCredentials(array $credentials): ?UserInterface;
}