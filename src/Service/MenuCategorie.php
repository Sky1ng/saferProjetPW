<?php


namespace App\Service;

use Knp\Menu\FactoryInterface;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;

class MenuCategorie
{
    private $factory;
    private $entityManager;

    /**
     * @param FactoryInterface $factory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FactoryInterface $factory, EntityManagerInterface $entityManager)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    public function createCategoryMenu()
    {
        $menu = $this->factory->createItem('root');

        $categories = $this->entityManager->getRepository(Categorie::class)->findAll();
        foreach ($categories as $category) {
            $menu->addChild($category->getNom(), [
                'route' => 'app_annonce',
                'routeParameters' => ['categorie' => $category->getNom()]
            ]);
        }

        return $menu;
    }
}