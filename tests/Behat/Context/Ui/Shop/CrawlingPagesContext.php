<?php

declare(strict_types=1);

namespace Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop\HomePage;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Page\Shop\Checkout;
use Webmozart\Assert\Assert;
use Tests\FiftyDeg\SyliusRobotsPlugin\Behat\Context\BaseContext;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CrawlingPagesContext extends BaseContext implements Context
{

    /**
     * @param HomePage $homePage
     * @param Checkout $checkout
     */
    public function __construct(private HomePage $homePage, 
        private Checkout $checkout, 
        private ChannelRepositoryInterface $repositoryChannel,
        private RepositoryInterface $repositoryLocale)
    {
    }

    /**
     * @var string
     */
    private $crawlerUserAgent = 'Googlebot';

    /**
     * @var string
     */
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

    private function checkUrlWithAllChannelsAndRobots($pageToCheck)
    {
        $allChannels = $this->repositoryChannel->findAll();
        $resultAllow = true;
        $resultDisAllow = true;
        $errorAllow = '';
        $errorDisAllow = '';
        $foundChannel = false;
        foreach($allChannels as $channel) {
            if($this->channelName != ''
                && $channel->getCode() != $this->channelName) {
                continue;
            }

            $foundChannel = true;

            $checkUrlWithChannelRobotsResults = $this->checkUrlWithChannelRobots($channel, $pageToCheck);
            if(isset($checkUrlWithChannelRobotsResults['disAllow'])
                && count($checkUrlWithChannelRobotsResults['disAllow']) > 0) {
                $resultDisAllow = false;
                $errorDisAllow .= $this->buildErrorsString($checkUrlWithChannelRobotsResults['disAllow'], $channel); 
            }

            if(isset($checkUrlWithChannelRobotsResults['allow'])
                && count($checkUrlWithChannelRobotsResults['allow']) > 0) {
                $resultAllow = false;
                $errorAllow .= $this->buildErrorsString($checkUrlWithChannelRobotsResults['allow'], $channel); 
            }
        }

        return [
            'foundChannel' => $foundChannel,
            'resultAllow' => $resultAllow,
            'resultDisAllow' => $resultDisAllow,
            'errorAllow' => $errorAllow,
            'errorDisAllow' => $errorDisAllow
        ];
    }

    /**
     * @Then I should be able to crawl the home page
     */
    public function iShouldBeAbleToCrawlHomePage(): void
    {
        $checkUrlWithAllChannelsAndRobots = $this->checkUrlWithAllChannelsAndRobots($this->homePage);

        Assert::true(
            $checkUrlWithAllChannelsAndRobots['foundChannel'],
            'Channel not found',
        );

        Assert::true(
            $checkUrlWithAllChannelsAndRobots['resultDisAllow'],
            $checkUrlWithAllChannelsAndRobots['errorDisAllow'],
        );
    }

    /**
     * @Then I should not be able to crawl the checkout page
     */
    public function iShouldNotBeAbleToCrawlCheckout(): void
    {
        $checkUrlWithAllChannelsAndRobots = $this->checkUrlWithAllChannelsAndRobots($this->checkout);

        Assert::true(
            $checkUrlWithAllChannelsAndRobots['foundChannel'],
            'Channel not found',
        );

        Assert::true(
            $checkUrlWithAllChannelsAndRobots['resultAllow'],
            $checkUrlWithAllChannelsAndRobots['errorAllow'],
        );
    }

    private function checkUrlWithChannelRobots($channel, $page)
    {
        $allLocales = $this->repositoryLocale->findAll();
        $allLocalesCodes = [];
        foreach($allLocales as $locale) {
            $allLocalesCodes[$locale->getCode()] = $locale->getCode();
        }
        $parsedPageUrl = parse_url($page->getUrl());
        $pageScheme = $parsedPageUrl['scheme'];
        $pagePath = strtolower($parsedPageUrl['path']);
        foreach($allLocalesCodes as $localeCode) {
            if(str_starts_with(ltrim($pagePath, '/'), strtolower($localeCode))) {
                $pagePath = str_replace('/' . strtolower($localeCode), '', $pagePath);
            }
        }
        $allUserAgentDirectives = $this->getAllUserAgentDirectives($channel, $pageScheme);

        if(!isset($allUserAgentDirectives[strtolower($this->crawlerUserAgent)])) {
            return false;
        }

        $userAgentDirective = $allUserAgentDirectives[strtolower($this->crawlerUserAgent)];

        $result = [];
        if($this->checkUrlWithRobot($pagePath, $userAgentDirective)) {
            $result['allow'][$userAgentDirective->userAgent] = ['pagePath' => $pagePath, 'userAgent' => $userAgentDirective];
        }
        else {
            $result['disAllow'][$userAgentDirective->userAgent] = ['pagePath' => $pagePath, 'userAgent' => $userAgentDirective];
        }
        return $result;
    }

    private function buildUserAgentDirective($disAllowItem, $pagePath)
    {
        $disAllowItemToUse = strtolower(rtrim($disAllowItem, "/"));
        $disAllowComponents = explode("/", $disAllowItemToUse);
        $pagePathComponents = explode("/", $pagePath);
        foreach($disAllowComponents as $disAllowComponentKey => $disAllowComponent) {
            if($disAllowComponent == '*'
                && isset($pagePathComponents[$disAllowComponentKey])) {
                $disAllowComponents[$disAllowComponentKey] = $pagePathComponents[$disAllowComponentKey];
            }
        }
        return(implode("/", $disAllowComponents));
    }

    private function checkUrlWithRobot($pagePath, $userAgent)
    {
        if($userAgent->disAllow
            && count($userAgent->disAllow) > 0) {
            foreach($userAgent->disAllow as $disAllowItem) {
                $disAllowItemToUse = $this->buildUserAgentDirective($disAllowItem, $pagePath);
                if(str_starts_with($pagePath, $disAllowItemToUse)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function getAllUserAgentDirectives($channel, $pageScheme)
    {
        $realPagePath = $pageScheme . '://' . $channel->getHostname() . '/';
        $robotsUrl = $realPagePath . "robots.txt";
        $userAgent = null;
        $allUserAgents = [];

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        
        $response = file_get_contents($robotsUrl, false, stream_context_create($arrContextOptions));

        $lines = explode("\n", $response); // this is your array of words

        foreach($lines as $line) {
            if (preg_match("/user-agent.*/i", $line) ){
                $userAgentName = trim(strtolower(explode(':', $line, 2)[1]));

                if($userAgent != null){
                    $allUserAgents[$userAgent->userAgent] = $userAgent;
                }

                $userAgent = new \stdClass();
                $userAgent->userAgent = strtolower(trim(explode(':', $line, 2)[1]));
                $userAgent->disAllow = [];
                $userAgent->allow = [];
            }
            if (preg_match("/disallow.*/i", $line)){
                array_push($userAgent->disAllow, trim(explode(':', $line, 2)[1]));
            }
            else if (preg_match("/^allow.*/i", $line)){
                array_push($userAgent->allow, trim(explode(':', $line, 2)[1]));
            }
        }

        if($userAgent != null){
            $allUserAgents[$userAgent->userAgent] = $userAgent;
        }

        return $allUserAgents;
    }

    private function buildErrorsString($checkChannelRobotsResults, $channel) 
    {
        $errorsString = '';
        if(count($checkChannelRobotsResults) > 0) {
            $status = false;
            foreach($checkChannelRobotsResults as $robotName => $checkChannelRobotsResultItem) {
                $errorsString .= $channel->getCode() . ' - ' . $channel->getName() . ' - ' . 'Error: ' . $robotName . ' - ' . $checkChannelRobotsResultItem['pagePath'] . '; ';
            }
        }
        return $errorsString;
    }
}
