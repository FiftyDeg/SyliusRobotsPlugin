<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Controller;

use FiftyDeg\SyliusRobotsPlugin\ConfigLoader\ConfigLoaderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


final class RobotsController {

    public function __construct(
        private ChannelContextInterface $channelContext,
        private ConfigLoaderInterface $configLoader
    ) {}

    public function __invoke(Request $request): Response
    {
        $channelCode = $this->channelContext->getChannel()->getcode();

        $robotsContent = $this->configLoader->getRobotsByChannelCode($channelCode);

        $response = new Response($robotsContent);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
