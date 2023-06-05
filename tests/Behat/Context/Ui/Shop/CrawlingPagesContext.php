<?php

declare(strict_types=1);

namespace Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Context\BaseContext;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop\Checkout;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop\HomePage;
use Webmozart\Assert\Assert;

final class CrawlingPagesContext extends BaseContext implements Context
{
    private HomePage $homePage;
    private Checkout $checkout;
    private ChannelRepositoryInterface $repositoryChannel;
    private RepositoryInterface $repositoryLocale;

    public function __construct(
        HomePage $homePage,
        Checkout $checkout,
        ChannelRepositoryInterface $repositoryChannel,
        RepositoryInterface $repositoryLocale
    ) {
        $this->homePage = $homePage;
        $this->checkout = $checkout;
        $this->repositoryChannel = $repositoryChannel;
        $this->repositoryLocale = $repositoryLocale;
    }

    /** @var string */
    private $crawlerUserAgent = 'Googlebot';

    /** @var string */
    private $channelName = '';

    /**
     * @Given I am a :crawlerUserAgent crawler
     */
    public function iAmACrawler(string $crawlerUserAgent): void
    {
        $this->crawlerUserAgent = $crawlerUserAgent;
    }

    /**
     * @Given I want to use :channelName channel
     */
    public function iWantToUseChannel(string $channelName): void
    {
        $this->channelName = $channelName;
    }

    /**
     * @Given I do not want to specify any channel
     */
    public function iDoNotWantToSpecifyAnyChannel(): void
    {
        $this->channelName = '';
    }

    private function checkUrlWithAllChannelsAndRobots(SymfonyPage $pageToCheck): array
    {
        $allChannels = $this->repositoryChannel->findAll();
        $resultAllow = true;
        $resultDisAllow = true;
        $errorAllow = '';
        $errorDisAllow = '';
        $foundChannel = false;
        foreach ($allChannels as $channel) {
            if ($this->channelName != '' &&
                $channel->getCode() != $this->channelName) {
                continue;
            }

            $foundChannel = true;

            $checkUrlRobots = $this->checkUrlWithChannelRobots($channel, $pageToCheck);
            if (isset($checkUrlRobots['disAllow']) &&
                count($checkUrlRobots['disAllow']) > 0) {
                $resultDisAllow = false;
                $errorDisAllow .= $this->buildErrorsString($checkUrlRobots['disAllow'], $channel);
            }

            if (isset($checkUrlRobots['allow']) &&
                count($checkUrlRobots['allow']) > 0) {
                $resultAllow = false;
                $errorAllow .= $this->buildErrorsString($checkUrlRobots['allow'], $channel);
            }
        }

        return [
            'foundChannel' => $foundChannel,
            'resultAllow' => $resultAllow,
            'resultDisAllow' => $resultDisAllow,
            'errorAllow' => $errorAllow,
            'errorDisAllow' => $errorDisAllow,
        ];
    }

    /**
     * @Then I should be able to crawl the home page
     */
    public function iShouldBeAbleToCrawlHomePage(): void
    {
        $checkUrlWithRobots = $this->checkUrlWithAllChannelsAndRobots($this->homePage);

        Assert::true(
            $checkUrlWithRobots['foundChannel'],
            'Channel not found',
        );

        Assert::true(
            $checkUrlWithRobots['resultDisAllow'],
            $checkUrlWithRobots['errorDisAllow'],
        );
    }

    /**
     * @Then I should not be able to crawl the checkout page
     */
    public function iShouldNotBeAbleToCrawlCheckout(): void
    {
        $checkUrlWithRobots = $this->checkUrlWithAllChannelsAndRobots($this->checkout);

        Assert::true(
            $checkUrlWithRobots['foundChannel'],
            'Channel not found',
        );

        Assert::true(
            $checkUrlWithRobots['resultAllow'],
            $checkUrlWithRobots['errorAllow'],
        );
    }

    private function checkUrlWithChannelRobots(Channel $channel, SymfonyPage $page): array
    {
        $allLocales = $this->repositoryLocale->findAll();
        $allLocalesCodes = [];
        foreach ($allLocales as $locale) {
            $allLocalesCodes[$locale->getCode()] = $locale->getCode();
        }
        $parsedPageUrl = parse_url($page->getUrl());
        $pageScheme = $parsedPageUrl['scheme'];
        $pagePath = strtolower($parsedPageUrl['path']);
        foreach ($allLocalesCodes as $localeCode) {
            if (str_starts_with(ltrim($pagePath, '/'), strtolower($localeCode))) {
                $pagePath = str_replace('/' . strtolower($localeCode), '', $pagePath);
            }
        }
        $allUserAgents = $this->getAllUserAgentDirectives($channel, $pageScheme);

        if (!isset($allUserAgents[strtolower($this->crawlerUserAgent)])) {
            return false;
        }

        $userAgentDirective = $allUserAgents[strtolower($this->crawlerUserAgent)];

        $result = [];
        $allowIndex = 'disAllow';
        if ($this->checkUrlWithRobot($pagePath, $userAgentDirective)) {
            $allowIndex = 'allow';
        }

        if (!isset($result[$allowIndex]) || !is_array($result[$allowIndex])) {
            $result[$allowIndex] = [];
        }

        $result[$allowIndex][$userAgentDirective->userAgent] = ['pagePath' => $pagePath, 'userAgent' => $userAgentDirective];

        return $result;
    }

    private function buildUserAgentDirective(string $disAllowItem, string $pagePath): string
    {
        $disAllowItemToUse = strtolower(rtrim($disAllowItem, '/'));
        $disAllowComponents = explode('/', $disAllowItemToUse);
        $pagePathComponents = explode('/', $pagePath);
        foreach ($disAllowComponents as $disAllowComponentKey => $disAllowComponent) {
            if ($disAllowComponent == '*' &&
                isset($pagePathComponents[$disAllowComponentKey])) {
                $disAllowComponents[$disAllowComponentKey] = $pagePathComponents[$disAllowComponentKey];
            }
        }

        return implode('/', $disAllowComponents);
    }

    private function checkUrlWithRobot(string $pagePath, object $userAgent): bool
    {
        if ($userAgent->disAllow &&
            count($userAgent->disAllow) > 0) {
            foreach ($userAgent->disAllow as $disAllowItem) {
                $disAllowItemToUse = $this->buildUserAgentDirective($disAllowItem, $pagePath);
                if (str_starts_with($pagePath, $disAllowItemToUse)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function getAllUserAgentDirectives(Channel $channel, string $pageScheme): array
    {
        $realPagePath = getenv('CONTAINER_NAME') . '/';
        $robotsUrl = $pageScheme . '://' . $realPagePath . 'robots.txt?_channel_code=' . $channel->getCode();
        $userAgent = null;
        $allUserAgents = [];

        $arrContextOptions = [
            'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
            ],
            'http' => [
                'protocol_version' => 1.1,
                'header'           => [
                    'Connection: close',
                ],
            ],
        ];

        $response = file_get_contents($robotsUrl, false, stream_context_create($arrContextOptions));

        $lines = explode("\n", $response); // this is your array of words

        foreach ($lines as $line) {
            if (preg_match('/user-agent.*/i', $line)) {
                if ($userAgent != null) {
                    $allUserAgents[$userAgent->userAgent] = $userAgent;
                }

                $userAgent = new \stdClass();
                $userAgent->userAgent = strtolower(trim(explode(':', $line, 2)[1]));
                $userAgent->disAllow = [];
                $userAgent->allow = [];
            }
            if (preg_match('/disallow.*/i', $line)) {
                array_push($userAgent->disAllow, trim(explode(':', $line, 2)[1]));
            } elseif (preg_match('/^allow.*/i', $line)) {
                array_push($userAgent->allow, trim(explode(':', $line, 2)[1]));
            }
        }

        if ($userAgent != null) {
            $allUserAgents[$userAgent->userAgent] = $userAgent;
        }

        return $allUserAgents;
    }

    private function buildErrorsString(array $checkRobots, Channel $channel): string
    {
        $errorsString = '';
        if (count($checkRobots) > 0) {
            foreach ($checkRobots as $robotName => $checkRobot) {
                $errorsString .= $channel->getCode() . ' - ' . $channel->getName() . ' - ' . 'Error: ' . $robotName . ' - ' . $checkRobot['pagePath'] . '; ';
            }
        }

        return $errorsString;
    }
}
