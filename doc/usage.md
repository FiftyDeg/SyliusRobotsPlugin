## Usage
------------------

<a href="../README.md" target="_blank">Back</a>
  
### Setup robots per channel
Create the `config/packages/fifty_deg_sylius_robots.yaml` file (you can also create it per environment) in order to configure per channel robots settings.  
`robots.txt` will be available at `https://example.com/robots.txt`.  
  
Below, a sample configuration:  

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