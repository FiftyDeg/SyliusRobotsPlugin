<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures;

use Doctrine\Persistence\ObjectManager;
use FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory\FiftyDegSyliusRobotsPluginChannelsFactory;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class FiftyDegSyliusRobotsPluginChannelsFixture extends AbstractFixture
{
    /** @var ObjectManager */
    private $channelManager;

    /** @var FiftyDegSyliusRobotsPluginChannelsFactory */
    private $channelsFactory;

    public function __construct(
        ObjectManager $channelManager,
        FiftyDegSyliusRobotsPluginChannelsFactory $channelsFactory2,
        private ChannelRepositoryInterface $repositoryChannel,
    ) {
        $this->channelManager = $channelManager;
        $this->channelsFactory = $channelsFactory2;
    }

    /** @param array<array-key, mixed> $options */
    public function load(array $options): void
    {
        /** @var array<array-key, ChannelInterface> $allChannels */
        $allChannels = $this->repositoryChannel->findAll();

        $alreadyAddedChannel = [];

        /** @var array<array-key, mixed> $newChannelOptions */
        foreach ($options as $newChannelOptions) {
            if(isset($alreadyAddedChannel[$newChannelOptions['code']])) {
                continue;
            }

            $checkOptions = $this->channelsFactory->checkOptionsFormats($newChannelOptions);

            if ($checkOptions !== '-1') {
                throw new \Exception($checkOptions);
            }

            $channelAlreadyExists = false;
            foreach ($allChannels as $channel) {
                /** @var string $newChannelCode */
                $newChannelCode = $newChannelOptions['code'];
                if ($channel->getCode() === $newChannelCode) {
                    $channel = $this->channelsFactory->update($channel, $newChannelOptions);
                    $this->channelManager->persist($channel);
                    $channelAlreadyExists = true;
                    $alreadyAddedChannel[$newChannelOptions['code']] = $newChannelOptions['code'];

                    break;
                }
            }

            if (!$channelAlreadyExists) {
                $channel = $this->channelsFactory->create($newChannelOptions);
                $this->channelManager->persist($channel);
                $alreadyAddedChannel[$newChannelOptions['code']] = $newChannelOptions['code'];
            }
        }

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

        $optionsChildren = $optionsNode->arrayPrototype()->children();
        $optionsChildren->scalarNode('name')->cannotBeEmpty();
        $optionsChildren->scalarNode('code')->cannotBeEmpty();
        $optionsChildren->scalarNode('hostname')->cannotBeEmpty();
        $optionsChildren->scalarNode('default_locale')->cannotBeEmpty();
        $optionsChildren->variableNode('locales')->beforeNormalization()->ifNull()->thenUnset();
        $optionsChildren->scalarNode('base_currency')->cannotBeEmpty();
        $optionsChildren->variableNode('currencies')->beforeNormalization()->ifNull()->thenUnset();
    }
}
