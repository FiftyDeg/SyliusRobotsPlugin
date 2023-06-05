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

    public function getRobotsByChannelCode(string $channelCode): ?string
    {
        /** @var array<int, array<string, string>> $channelsConf */
        $channelsConf = $this->getParam('channels') ?? [];

        foreach ($channelsConf as $channelConf) {
            if (
                isset($channelConf['code']) &&
                $channelConf['code'] === $channelCode
            ) {
                return $channelConf['robots_content'];
            }
        }

        return null;
    }

    public function getDefaultRobots(): ?string
    {
        /** @var array<int, array<string, string>> $defaultConf */
        $defaultConf = $this->getParam('default') ?? [];

        if (count($defaultConf) === 1) {
            return $defaultConf[0]['robots_content'];
        }

        return null;
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
