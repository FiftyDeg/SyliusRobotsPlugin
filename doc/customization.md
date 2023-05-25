## Customization
------------------

For now the only customizations you can do are in `/config/packages/fifty_deg_sylius_robots.yaml`, the file containing data similar to the ones described below, where - for a variable set of channels - there is linked robots to use.

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

4. Clear application cache by using command:
```
$ bin/console cache:clear
```