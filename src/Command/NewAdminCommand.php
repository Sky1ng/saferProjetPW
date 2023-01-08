<?php

namespace App\Command;

use App\Entity\Admin;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;


class NewAdminCommand extends Command implements ContainerAwareInterface
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
            ->setName('app:newAdmin')
            ->setDescription('Créer un nouvel administrateur')
            ->setHelp('Cette commande permet de créer un nouvelle administrateur selon l\'id donnée en argument')
            ->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to promote');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('user-id');

        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $userId = $input->getArgument('user-id');

        // Récupérez l'utilisateur à partir de la base de données
        $user = $entityManager->getRepository(Admin::class)->find($userId);
        if (!$user) {
            throw new \InvalidArgumentException(sprintf('User with ID "%s" not found', $userId));
        }

        // Récupérez l'utilisateur et modifiez ses rôles...
        if (!in_array('ROLE_DEFAULT', $user->getRoles()) || !in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles(['ROLE_DEFAULT', 'ROLE_ADMIN']);
            $entityManager->flush();
            $output->writeln(sprintf('User with ID "%s" now has the roles ["ROLE_DEFAULT", "ROLE_ADMIN"]', $userId));
        } else {
            $output->writeln(sprintf('User with ID "%s" already has the roles ["ROLE_DEFAULT", "ROLE_ADMIN"]', $userId));
        }
        return Command::SUCCESS;
    }
}
