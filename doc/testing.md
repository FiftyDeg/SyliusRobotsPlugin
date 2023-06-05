<a href="../README.md" target="_blank">Back</a>

## Testing

1. Start docker compose in test mode
```
$ cd .docker && ./bin/start_test.sh
```

1. Wait docker to be up and running...

2. Start static analysis and Behat tests
```
$ cd .docker && ./bin/php_test.sh
```
<br/>

### BDD
------------
A suite for BDD testing is already present; it is registered in `/tests/Behat/Resources/services.yml`, you can find the features in `/features`, the contexts registered in `/tests/Behat/Resources/suites.yml`, and the asscoiated PHP code in /tests/Behat/Context/Ui/Shop.

Lastly, you have to add `tests/Application/config/packages/test/fifty_deg_sylius_robots.yaml`, containing data similar to the ones described below, where - for a variable set of channels - there is linked robots to use.

```
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
                Disallow: /checkout

                User-agent: facebot
                            facebookexternalhit/1.0 (+http://www.facebook.com/externalhit_uatext.php)
                            facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
                Disallow: /checkout/*

                User-agent: Applebot
                Disallow: /checkout
        -   code: 'DISALLOW_CHECKOUT'
            robots_content: | 
                User-agent: Googlebot
                Disallow: /checkout/

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
```
