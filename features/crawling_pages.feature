@ROBOTS
Feature: Crawling Pages feature

  Scenario: Testing robots.txt block
    Given I am a "Googlebot/2.1" crawler
    Then I should be able to crawl the home page
    And I should not be able to crawl the checkout page
