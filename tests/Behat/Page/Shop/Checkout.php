<?php

declare(strict_types=1);

namespace Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class Checkout extends SymfonyPage
{
    /**
     * @inheritdoc
     */
    public function getRouteName(): string
    {
        return 'sylius_shop_checkout_address';
    }

    public function getUrl(array $urlParameters = []): string
    {
        return parent::getUrl($urlParameters);
    }
}
