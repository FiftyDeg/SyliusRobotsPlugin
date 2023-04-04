<?php

declare(strict_types=1);

namespace Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

class Checkout extends SymfonyPage implements SymfonyPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        //return 'sylius_shop_homepage';
        return 'https://127.0.0.1:8080/checkout';
    }
}
