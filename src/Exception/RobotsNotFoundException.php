<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Exception;

final class RobotsNotFoundException extends \RuntimeException
{
    public function __construct(?string $channelCode)
    {
        $message = null !== $channelCode
            ? 'Robots configuration not found for channel ' . $channelCode . '.'
            : 'Robots configuration not found.';

        parent::__construct($message, 0);
    }
}
