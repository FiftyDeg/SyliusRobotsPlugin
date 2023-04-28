<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\ConfigLoader;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class ConfigLoader implements ConfigLoaderInterface
{
    public function __construct(
        private ParameterBag $parameterBag,
    ) {
    }

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

    private function getParam(string $paramName): mixed
    {
        return $this->parameterBag->has($paramName)
            ? $this->parameterBag->get($paramName)
            : null;
    }
}
