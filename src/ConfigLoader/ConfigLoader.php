<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\ConfigLoader;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class ConfigLoader implements ConfigLoaderInterface
{
    public function __construct(
        private ParameterBag $parameterBag
    )
    {
    }


    public function getRobotsByChannelCode(string $channelCode): string
    {
        $channelsConf = $this->getParam('channels') ?? [];
        $defaultConf = $this->getParam('default') ?? [];

        foreach($channelsConf as $channelConf) {
            if (isset($channelConf["code"])
                && $channelConf["code"] === $channelCode) {
                return $channelConf["robots_content"];
            }
        }

        return $defaultConf["robots_content"];
    }

    private function getParam(string $paramName): mixed
    {
        return $this->parameterBag->has($paramName)
            ? $this->parameterBag->get($paramName)
            : null;
    }
}
