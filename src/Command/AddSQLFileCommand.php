<?php

namespace App\Command;

use Doctrine\DBAL\DriverManager;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AddSQLFileCommand extends Command implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }


    protected function configure(): void
    {
        $this
            ->setName('app:addSQLFile')
            ->setDescription('Add a SQL file to the database')
            ->setHelp('This command allows you to add a SQL file to the database')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Displays the SQL file that would be added without adding it to the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérez le chemin vers votre fichier SQL
        $sqlFilePath = 'config/data/sqlFile.sql';

        // Récupérez les paramètres de connexion à votre base de données
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $conn = $entityManager->getConnection();

        // Lisez le contenu du fichier SQL
        $sql = file_get_contents($sqlFilePath);

        // Exécutez chaque instruction SQL séparément
        if(!$input->getOption('dry-run')) {
            $conn->query($sql);
        } else {
            $output->writeln($sql);
        }

        return Command::SUCCESS;
    }
}
