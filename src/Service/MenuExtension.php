<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\MenuCategorie;

class MenuExtension extends AbstractExtension
{
    private $menuBuilder;

    public function __construct(MenuCategorie $menuBuilder)
    {
        $this->menuBuilder = $menuBuilder;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('category_menu', [$this, 'createCategoryMenu'])
        ];
    }

    public function createCategoryMenu()
    {
        return $this->menuBuilder->createCategoryMenu();
    }
}