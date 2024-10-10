<?php

namespace App\Command;

use App\DataFixtures\CategoryFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDataFixturesCommand extends Command
{
    protected static $defaultName        = 'app:generate-data-fixtures';
    protected static $defaultDescription = 'Load data fixtures';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $loader = new Loader();
        $loader->loadFromDirectory(FIXTURES_PATH);

        $executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $executor->execute($loader->getFixtures());

        return Command::SUCCESS;
    }

}