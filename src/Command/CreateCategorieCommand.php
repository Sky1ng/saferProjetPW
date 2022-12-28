<?php

namespace App\Command;

use App\Entity\Categorie;

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

class CreateCategorieCommand extends Command implements ContainerAwareInterface
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
            ->setName('app:create-categorie')
            ->setDescription('Crée des catégories dans la base de données')
            ->setHelp('Cette commande vous permet de créer des catégories dans votre base de données')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Affiche les catégories qui seraient créées sans les ajouter à la base de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $categories = [
            ['type' => 'Terrain agricole', 'nom' => 'Terrain agricole'],
            ['type' => 'Prairie', 'nom' => 'Prairie'],
            ['type' => 'Bois', 'nom' => 'Bois'],
            ['type' => 'Batiments', 'nom' => 'Batiments'],
            ['type' => 'Exploitations', 'nom' => 'Exploitations'],
        ];

        // Récupérez l'EntityManager depuis le container
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        //Supprimer le contenu de la table categorie
        if (!$input->getOption('dry-run')) {
            $query = $entityManager->createQuery('DELETE FROM App:Categorie');
            $query->execute();
        }

        // Parc --ourez les catégories et créez un objet Categorie pour chaque entrée
        foreach ($categories as $categoryData) {
            $category = new Categorie();
            $category->setType($categoryData['type']);
            $category->setNom($categoryData['nom']);

            // Si l'option "dry-run" a été passée, affichez simplement les catégories qui seraient créées
            if ($input->getOption('dry-run')) {
                $output->writeln(sprintf('Ajout de la catégorie "%s" (%s)', $category->getNom(), $category->getType()));
            } else {
                // Sinon, ajoutez la catégorie à la base de données
                $entityManager->persist($category);
                $output->writeln(sprintf('Ajout de la catégorie "%s" (%s) à la base de données', $category->getNom(), $category->getType()));
            }
        }

        // Si l'option "dry-run" n'a pas été passée, enregistrez les modifications en base de données
        if (!$input->getOption('dry-run')) {
            $entityManager->flush();
            $output->writeln('Ajout de toutes les catégories à la base de données terminé');
        }
        return Command::SUCCESS;
    }
}
