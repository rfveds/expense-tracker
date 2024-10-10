<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(): void
    {
        $this->createMany(100, 'categories', function () {
            $category = new Category();
            $category->setName($this->faker->unique()->word);
            $category->setUser($this->getRandomReference('users'));

            return $category;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
