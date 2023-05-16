<?php

declare(strict_types=1);

namespace Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class HomePage extends SymfonyPage
{
    /**
     * @inheritdoc
     */
    public function getRouteName(): string
    {
        return 'sylius_shop_homepage';
    }

    public function getUrl(array $urlParameters = []): string
    {
        return parent::getUrl($urlParameters);
    }
}
