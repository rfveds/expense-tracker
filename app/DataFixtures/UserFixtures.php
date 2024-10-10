<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\HashService;

class UserFixtures extends AbstractBaseFixtures
{
    private ?HashService $hashService = null;

    public function loadData(): void
    {
        $this->hashService = new HashService();

        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'users', function (int $i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setPassword(
                $this->hashService->hashPassword(
                    'user1234'
                )
            );
            $user->setName($this->faker->name);
            $user->setVerifiedAt(new \DateTime());

            return $user;
        });

        $this->manager->flush();
    }
}
