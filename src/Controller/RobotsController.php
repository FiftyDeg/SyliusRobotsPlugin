<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Controller;

use FiftyDeg\SyliusRobotsPlugin\ConfigLoader\ConfigLoaderInterface;
use FiftyDeg\SyliusRobotsPlugin\Exception\RobotsNotFoundException as ExceptionRobotsNotFoundException;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
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
        $channelCode = $this->channelContext->getChannel()->getCode();

        if (null === $channelCode) {
            throw new ChannelNotFoundException();
        }

        $robotsContent = $this->configLoader->getRobotsByChannelCode($channelCode);

        if (null === $robotsContent) {
            $robotsContent = $this->configLoader->getDefaultRobots();
        }

        if (null === $robotsContent) {
            throw new ExceptionRobotsNotFoundException($channelCode);
        }

        $response = new Response($robotsContent);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
