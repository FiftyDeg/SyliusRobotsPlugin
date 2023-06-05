<a href="../README.md" target="_blank">Back</a>

## Testing

1. Start docker compose in test mode
```
$ cd .docker && ./bin/start_test.sh
```

2. Wait docker to be up and running...

3. Run start static analysis and Behat tests
```
$ cd .docker && ./bin/php_test.sh
```
<br/>

## BDD
A suite for BDD testing is defined in `tests/Behat/Resources/services.yml`.  
Features are defined in `features`, and contexts in `tests/Behat/Resources/suites.yml`.

## :warning: Configuration
In order to test robots, we need to crawl pages: this means that, when performing a request to the server, the server will load the `dev` environment. 
Therefore, to setup the test configuration, you need to update both `tests/Application/config/packages/test/fifty_deg_sylius_robots.yaml` and `tests/Application/config/packages/dev/fifty_deg_sylius_robots.yaml`.  
Below, a sample configuration.

```yaml
fifty_deg_sylius_robots:
    channels:
        -   code: 'FASHION_WEB'
            robots_content: | 
                User-agent: Googlebot
                Disallow: /checkout

                User-agent: Bingbot
                Disallow: /checkout/*

                User-agent: Slurp
                Disallow: /checkout/*

                User-agent: DuckDuckBot
                Disallow: /checkout/*

                User-agent: Baiduspider
                Disallow: /checkout/*

                User-agent: YandexBot
                Disallow: /checkout/*

                User-agent: facebot
                            facebookexternalhit/1.0 (+http://www.facebook.com/externalhit_uatext.php)
                            facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
                Disallow: /checkout/*

                User-agent: Applebot
                Disallow: /checkout/*
        -   code: 'DISALLOW_CHECKOUT'
            robots_content: | 
                User-agent: Googlebot
                Disallow: /checkout/*

                User-agent: Bingbot
                Disallow: /checkout/*

                User-agent: Slurp
                Disallow: /checkout/*

                User-agent: DuckDuckBot
                Disallow: /checkout/*

                User-agent: Baiduspider
                Disallow: /checkout/*

                User-agent: YandexBot
                Disallow: /checkout/*

                User-agent: facebot
                            facebookexternalhit/1.0 (+http://www.facebook.com/externalhit_uatext.php)
                            facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
                Disallow: /checkout/*

                User-agent: Applebot
                Disallow: /checkout/*
    default:
        -   robots_content: | 
                User-agent: Googlebot
                Disallow: /

                User-agent: Bingbot
                Disallow: /checkout/*

                User-agent: Slurp
                Disallow: /checkout/*

                User-agent: DuckDuckBot
                Disallow: /checkout/*

                User-agent: Baiduspider
                Disallow: /checkout/*

                User-agent: YandexBot
                Disallow: /checkout/*

                User-agent: facebot
                            facebookexternalhit/1.0 (+http://www.facebook.com/externalhit_uatext.php)
                            facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
                Disallow: /checkout/*

                User-agent: Applebot
                Disallow: /checkout/*
```
