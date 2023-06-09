<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures;

use Doctrine\Persistence\ObjectManager;
use FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory\FiftyDegSyliusRobotsPluginChannelsFactory;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This class implements a Fixture, https://docs.sylius.com/en/1.12/customization/fixtures.html
 * it reads data from a configuration file, fiftydeg_sylius_robots_channels_suite.yaml, 
 * and use them to create/update the channels into the Sylius catalog.
 *
 * @author Marco Pistorello <marco.pistorello@fiftydeg.com>
 */
class FiftyDegSyliusRobotsPluginChannelsFixture extends AbstractFixture
{
    /** @var ObjectManager */
    private $channelManager;

    /** @var FiftyDegSyliusRobotsPluginChannelsFactory */
    private $channelsFactory;

    private ChannelRepositoryInterface $repositoryChannel;

    public function __construct(
        ObjectManager $channelManager,
        FiftyDegSyliusRobotsPluginChannelsFactory $channelFactory,
        ChannelRepositoryInterface $repositoryChannel
    ) {
        $this->channelManager = $channelManager;
        $this->channelsFactory = $channelFactory;
        $this->repositoryChannel = $repositoryChannel;
    }

    /**
     * Iterates all the channels from the configuration file fiftydeg_sylius_robots_channels_suite.yaml:
     * - if the channel is already present, the code will update it with the data found in the file
     * - if the channel is no already present, the code will add it.
     * 
     * @param array<array-key, mixed> $options
     * @return void
     */
    public function load(array $options): void
    {
        /** @var array<array-key, ChannelInterface> $allChannels */
        $allChannels = $this->repositoryChannel->findAll();

        /** @var array<string, string> $alreadyAddedChannel */
        $alreadyAddedChannel = [];

        /** @var array<array-key, mixed> $newChannelOptions */
        foreach ($options as $newChannelOptions) {
            /** @var string $newChannelCode */
            $newChannelCode = $newChannelOptions['code'];

            if (isset($alreadyAddedChannel[$newChannelCode])) {
                continue;
            }

            $checkOptions = $this->channelsFactory->checkOptionsFormats($newChannelOptions);

            if (!is_null($checkOptions)) {
                throw new \Exception($checkOptions);
            }

            $channelAlreadyExists = false;
            foreach ($allChannels as $channel) {
                if ($channel->getCode() === $newChannelCode) {
                    $channel = $this->channelsFactory->update($channel, $newChannelOptions);
                    $this->channelManager->persist($channel);
                    $channelAlreadyExists = true;
                    $alreadyAddedChannel[$newChannelCode] = $newChannelCode;

                    break;
                }
            }

            if (!$channelAlreadyExists) {
                $channel = $this->channelsFactory->create($newChannelOptions);
                $this->channelManager->persist($channel);
                $alreadyAddedChannel[$newChannelCode] = $newChannelCode;
            }
        }

        $this->channelManager->flush();
    }

    public function getName(): string
    {
        return 'fiftydeg_sylius_robots_plugin_channels_fixture';
    }

    /**
     * Configure the types of the data contained in the configuration file.
     * It could contain further infos, according with /src/Resources/config/vendor/fixtures/fiftydeg_sylius_robots_channels_suite.yaml
     * 
     * @param ArrayNodeDefinition $optionsNode
     * @return void
     */
    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
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
