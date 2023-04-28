<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures\Listener;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\AfterFixtureListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\FixtureEvent;

/*
It's a good idea to not throw this file away, because it could go implement great functionalities, especially before and after the fixture execution.
*/

final class FiftyDegSyliusRobotsPluginChannelsListener extends AbstractListener implements AfterFixtureListenerInterface
{
    /** @var ObjectManager */
    private $channelManager;

    public function __construct(ObjectManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function getName(): string
    {
        return 'fiftydeg_sylius_robots_plugin_channels_listener';
    }

    public function afterFixture(FixtureEvent $fixtureEvent, array $options): void
    {
        /*
        Even if this function is empty and all this file could be useless,
        it's a good idea to not throw it away, because it could go implement great functionalities, especially before and after the fixture execution.
        */
    }
}
