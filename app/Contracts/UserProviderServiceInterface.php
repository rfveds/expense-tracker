<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DataObjects\RegisterUserData;

interface UserProviderServiceInterface
{
    public function findById(int $id): ?UserInterface;

    public function findByCredentials(array $credentials): ?UserInterface;

    public function createUser(RegisterUserData $data): ?UserInterface;

    public function verifyUser(UserInterface $user): void;

}