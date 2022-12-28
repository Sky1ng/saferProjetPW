<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Bien;
use App\Entity\Categorie;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CsvToDBBienCommand extends Command implements ContainerAwareInterface
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

    protected function configure()
    {
        $this
            ->setName('app:csvToDBBien')
            ->setDescription('Convertit un CSV vers la base de donnée pour des biens')
            ->setHelp('Cette commande vous permet de convertir un CSV vers la base de donnée pour des biens')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Affiche les biens qui seraient créées sans les ajouter à la base de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $handle = fopen('config/data/data_safer.csv', 'r');

        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            // Crée une instance de l'entité Bien
            $bien = new Bien();
            $bien->setReference($data[0]);
            $bien->setTitre($data[1]);
            $bien->setDescription($data[2]);
            $bien->setLocalisation($data[3]);
            $bien->setSurface(intval($data[4]));
            $bien->setPrix(intval($data[5]));
            $bien->setType($data[6]);

            // Récupère la catégorie correspondant au type du bien
            $categorie = $entityManager->getRepository(Categorie::class)->findOneBy(['type' => $data[7]]);
            $bien->setIdCategorie($categorie);
            $categorie->addBien($bien);

            // Si l'option "dry-run" a été passée, affichez simplement les catégories qui seraient créées
            if ($input->getOption('dry-run')) {
                $output->writeln(sprintf('Ajout du bien "%s"', $bien->getTitre()));

            } else {
                // Sinon, ajoutez la catégorie à la base de données
                $entityManager->persist($bien);
                $entityManager->persist($categorie);
                $output->writeln(sprintf('Ajout du bien "%s" à la base de données', $bien->getTitre()));
            }
        }


        if (!$input->getOption('dry-run')) {
            $entityManager->flush();
            $output->writeln('Ajout de toutes les biens à la base de données terminé');
        }

        return Command::SUCCESS;
    }
}
