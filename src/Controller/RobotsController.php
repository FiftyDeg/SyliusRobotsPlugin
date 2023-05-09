<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Controller;

use FiftyDeg\SyliusRobotsPlugin\ConfigLoader\ConfigLoaderInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RobotsController
{
    private ChannelContextInterface $channelContext;
    private ConfigLoaderInterface $configLoader;

    public function __construct(
        ChannelContextInterface $channelContext,
        ConfigLoaderInterface $configLoader
    ) {
        $this->channelContext = $channelContext;
        $this->configLoader = $configLoader;
    }

    public function __invoke(Request $request): Response
    {
        /** @var string $channelCode */
        $channelCode = $this->channelContext->getChannel()->getCode();

        $robotsContent = $this->configLoader->getRobotsByChannelCode($channelCode);

        $response = new Response($robotsContent);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
