<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures\Listener;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\AfterFixtureListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\FixtureEvent;

/**
 * This class implements a Listener linked to the FiftyDegSyliusRobotsPluginChannelsFixture, 
 * triggered with specified events, listed in AbstractListener
 *
 * @author Marco Pistorello <marco.pistorello@fiftydeg.com>
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

    /**
     * Implements the actions to run after FiftyDegSyliusRobotsPluginChannelsFixture has been executed.
     * 
     * @param FixtureEvent $fixtureEvent
     * @param array $options
     * @return void
     */
    public function afterFixture(FixtureEvent $fixtureEvent, array $options): void
    {
    }
}
