<?php

namespace App\Navigation;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\RequestStack;

class NavigationBuilder
{
    private FactoryInterface $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainNavigation(RequestStack $requestStack): MenuItem
    {
        $navigation = $this->factory->createItem('root');

        $navigation->addChild('Home', ['route' => 'index']);

        return $navigation;
    }
}