<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures;

use FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory\FiftyDegSyliusRobotsPluginChannelsFactory;
use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

class FiftyDegSyliusRobotsPluginChannelsFixture extends AbstractFixture
{
    /**
     * @var ObjectManager
     */
    private $channelManager;

    /**
     * @var FiftyDegSyliusRobotsPluginChannelsFactory
     */
    private $channelsFactory;

    public function __construct(ObjectManager $channelManager, 
        FiftyDegSyliusRobotsPluginChannelsFactory $customchannelsFactory,
        private ChannelRepositoryInterface $repositoryChannel)
    {
        $this->channelManager = $channelManager;
        $this->channelsFactory = $customchannelsFactory;
    }

    public function load(array $options): void
    {
        $allChannels = $this->repositoryChannel->findAll();
        foreach($allChannels as $channel) {
            if($channel->getCode() != "FASHION_WEB") {
                continue;
            }

            $channel->setHostname('syliusplugin.local');
            $this->channelManager->persist($channel);
        }

        $channel = $this->channelsFactory->create($options);
        $this->channelManager->persist($channel);
        $this->channelManager->flush();
    }

    public function getName(): string
    {
        return 'fiftydeg_sylius_robots_plugin_channels_fixture';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        /*
        Attention, don't delete this comment.
        This function should contains further infos, according with /src/Resources/config/vendor/fixtures/fiftydeg_sylius_robots_channels_suite.yaml
        */
        $optionsNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->scalarNode('hostname')->end()
                ->scalarNode('default_locale')->cannotBeEmpty()->end()
                ->variableNode('locales')
                    ->beforeNormalization()
                        ->ifNull()->thenUnset()
                    ->end()
                ->end()
                ->scalarNode('base_currency')->cannotBeEmpty()->end()
                ->variableNode('currencies')
                    ->beforeNormalization()
                        ->ifNull()->thenUnset()
                    ->end()
                ->end()
        ;
    }
}