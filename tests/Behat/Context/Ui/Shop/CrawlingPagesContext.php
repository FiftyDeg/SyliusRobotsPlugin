<?php

declare(strict_types=1);

namespace Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;
use MarcOrtola\BehatSEOContexts\Context\RobotsContext;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop\HomePage;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop\Checkout;

final class CrawlingPagesContext extends RobotsContext implements Context
{
    /**
     * @var HomePage
     */
    private $homePage;

    /**
     * @var Checkout
     */
    private $checkout;

    /**
     * @param HomePage $homePage
     * @param Checkout $checkout
     */
    public function __construct(HomePage $homePage, Checkout $checkout)
    {
        $this->homePage = $homePage;
        $this->checkout = $checkout;
    }

    /**
     * @Then I should be able to crawl the home page
     */
    public function iShouldBeAbleToCrawlHomePage(): void
    {
        parent::iShouldBeAbleToCrawl($this->homePage->getRouteName());
    }

    /**
     * @Then I should not be able to crawl the checkout page
     */
    public function iShouldNotBeAbleToCrawlCheckout(): void
    {
        parent::iShouldNotBeAbleToCrawl($this->checkout->getRouteName());
    }
}
