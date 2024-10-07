<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\DataObjects\RegisterUserData;
use App\Entity\User;

readonly class UserProviderService implements UserProviderServiceInterface
{
    public function __construct(private EntityManagerServiceInterface $entityManager)
    {
    }

    public function findById(int $id): ?UserInterface
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function findByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
    }

    public function createUser(RegisterUserData $data): UserInterface
    {
        $user = new User();

        $user->setName($data->name);
        $user->setEmail($data->email);
        $user->setPassword(password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 12]));

        $this->entityManager->sync($user);

        return $user;
    }

    public function verifyUser(UserInterface $user): void
    {
        $user->setVerifiedAt(new \DateTime());

        $this->entityManager->sync($user);
    }
}