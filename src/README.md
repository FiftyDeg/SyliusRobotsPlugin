# Description
This plugin allows you to define per channel and per environment `robots.txt` file.  
  
# Install

### Step 1: Enable the plugin inside bundles.php

In `config/bundles.php` add  

``` php
    FiftyDeg/SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true]
```

### Step 2: Register routes and vendor settings
In order to register routes, add the following code snippet in `config/routes.yaml`:  
```yaml
fiftydeg_sylius_robots_plugin:
    resource: "@FiftyDegSyliusRobotsPlugin/Resources/config/routes.yaml"
```
In `config/services.yaml` remove this bundle from autowiring and register vendor settings:  

```yaml
services:
    App\:
        resource: '../src/*'
        exclude: '../src/{FiftyDeg/Robots,Entity,Migrations,Tests,Kernel.php}'
```
### Step 3: PHPStan
If you're using PHPStan, please, consider adding the following rules in `<project_root>/phpstan.neon` in order to prevent dependency injection validation errors:  
```yaml
parameters:
    ignoreErrors:
    - '#Call to an undefined method Symfony\\Component\\Config\\Definition\\Builder\\NodeParentInterface::scalarNode\(\).#'
    - '#Call to an undefined method Symfony\\Component\\Config\\Definition\\Builder\\NodeParentInterface::arrayNode\(\).#'
```
  
# Usage
  
### Setup robots per channel
Create the `config/packages/fiftydeg_robots.yaml` file (you can also create it per environment) in order to configure per channel robots settings.  
`robots.txt` will be available at `https://example.com/robots.txt`.  
  
Below, a sample configuration:  
```yaml
fifty_deg_sylius_robots:
    channels:
        -   code: 'default'
            robots_content: | 
                User-agent: *
                Allow: /
                Disallow: /admin
                Disallow: /*/cart
                Disallow: /*/checkout
                Disallow: /*/search

                Sitemap: https://mysyliussite.com/sitemap_index.xml

        -   code: 'channel_code'
            robots_content: |
                User-agent: *
                Disallow: /
```
