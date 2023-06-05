<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\ConfigLoader;

interface ConfigLoaderInterface
{
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
    public function getRobotsByChannelCode(string $channelCode): string;
}
