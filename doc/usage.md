<a href="../README.md" target="_blank">Back</a>

## Usage
  
### Setup robots per channel
1. delete the `robots.txt` static file in `/public` directory.
2. create the `config/packages/fifty_deg_sylius_robots.yaml` file (you can also create it per environment) in order to configure per channel robots settings.  
3.  `robots.txt` will be available at `https://example.com/robots.txt`.  
  
Below, a sample configuration:  

```yaml
fifty_deg_sylius_robots:
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
```

---

<a href="./customization.md" target="_blank">Next: Customization</a>
