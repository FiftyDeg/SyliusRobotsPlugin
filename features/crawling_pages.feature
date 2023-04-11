@crawling_pages @ui
Feature: Crawling Pages feature

  Scenario: Testing robots.txt block
    Given I am a "Googlebot" crawler
    And I want to use "DISALLOW_CHECKOUT" channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "Bingbot" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "Slurp" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "DuckDuckBot" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "Baiduspider" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "YandexBot" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "facebot" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page

    Given I am a "Applebot" crawler
    And I do not want to specify any channel
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page