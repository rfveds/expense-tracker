<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\User;
use App\Services\FileService;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TransactionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    protected function loadData(): void
    {
        $this->createMany(100, 'transactions', function () {
            /** @var User $user */
            $user               = $this->getRandomReference('users');
            $categories         = $user->getCategories()->toArray();
            $randomUserCategory = array_rand($categories);

            $transaction = new Transaction();
            $transaction->setDescription($this->faker->sentence);
            $transaction->setUser($user);
            $transaction->setAmount($this->faker->randomFloat(min: -10000, max: 10000));
            $transaction->setDate(new \DateTime());
            $transaction->setCategory($categories[$randomUserCategory]);

            return $transaction;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}