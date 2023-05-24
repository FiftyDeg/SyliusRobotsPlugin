<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\ConfigLoader;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * This class implements a configuration loader,
 * in other words it implements all the functions needed to load the data about the crawler bots intended to be tested.
 *
 * @author Marco Pistorello <marco.pistorello@fiftydeg.com>
 */
final class ConfigLoader implements ConfigLoaderInterface
{
    private ParameterBag $parameterBag;

    public function __construct(
        ParameterBag $parameterBag
    ) {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Iterates through the var channels in the configuration file, fifty_deg_sylius_robots.yaml,
     * until it finds the correct channel code (code variable) equal to $channelCode
     *
     * Returns the configuration variables, specified for each crawler bot
     * 
     * @param string $channelCode
     * @return string
     * @throws \Exception If $channelCode is not found in the configuration file, fifty_deg_sylius_robots.yaml
     */
    public function getRobotsByChannelCode(string $channelCode): string
    {
        /** @var array<int, array<string, string>> $channelsConf */
        $channelsConf = $this->getParam('channels') ?? [];

        /** @var array<int, array<string, string>> $defaultConf */
        $defaultConf = $this->getParam('default') ?? [];

        foreach ($channelsConf as $channelConf) {
            if (isset($channelConf['code']) &&
                $channelConf['code'] === $channelCode) {
                return $channelConf['robots_content'];
            }
        }

        if (count($defaultConf) == 1) {
            return $defaultConf[0]['robots_content'];
        }

        throw new \Exception('no Default Configuration or too many default configuration for robots yaml');
    }

    /**
     * @return array|bool|string|int|float|null
     */
    private function getParam(string $paramName)
    {
        return $this->parameterBag->has($paramName)
            ? $this->parameterBag->get($paramName)
            : null;
    }
}
