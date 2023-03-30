<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\ConfigLoader;

interface ConfigLoaderInterface
{
    public function getRobotsByChannelCode(string $channelCode): string;
}
