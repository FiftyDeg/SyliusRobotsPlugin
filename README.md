<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
    <a href="https://sylius.com" target="_blank">
        <img src="doc/fd.png" />
    </a>
</p>

<h1 align="center">Sylius Robots Plugin</h1>

<p align="center">A Sylius plugin that allows configuring different sitemaps per each channel.</p>


## About US
------------------

We want to unleash people's potential by returning the most important resource: time!
------------------
We think that time is the most important resource, for this reason we want to free it by assigning the right professionalism to the required task, whether it is a digital project for third parties or an own software.

<a href="https://www.linkedin.com/company/fiftydeg/" target="_blank" rel="nooperer noreferrer">Linked In</a>

## Documentation
------------------

For a comprehensive guide on Sylius Plugins development please go to Sylius documentation,
there you will find the <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development Guide</a>, that is full of examples.

<ul>
<li><a href="doc/installation.md">Installation</a></li>
</ul>

## Quickstart Installation
------------------

### Installing with Composer

```
composer require fifty-deg/sylius-robots-plugin
```

## Development
------------------

### Docker

1. Execute `cd ./.docker && ./bin/start_dev.sh`
2. Configure `/etc/hosts` adding the line `127.0.0.1    syliusplugin.local`
3. Add `FiftyDeg\SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true],` into /config/bundles.php 
4. In /config/packages/<the-environment-you-are-using:dev|test|prod>/fifty_deg_sylius_robots.yaml insert your robots configurations, detailed per channel.
5. In case you wish to have more channels in different hostname, remember to add all the channel hostname that you need into docker-compose.yml, under `extra_hosts`, specifying also the exact port. For example `mycompany.com:127.0.0.1`


### In case you need some extra channel for testing, you could add any automatically with a Fixture
1. In /config/packages/<the-environment-you-are-using:dev|test|prod>/fiftydeg_sylius_robots_channels_suite.yaml, you can add all the channels you need automatically, with a Fixture.
2. Add the line `&& yes | php bin/console sylius:install --no-interaction --verbose --env ${APP_ENV}` in docker-compose.yml, after the line `&& yes | php bin/console sylius:install:check-requirements`, where ${APP_ENV} should be replace with the environment you are using right now, i.e. `dev|test|prod`
and the line `&& yes | php bin/console sylius:fixtures:load fiftydeg_sylius_robots_plugin_channels_suite --no-interaction` after `&& yes | php bin/console sylius:fixtures:load default --no-interaction`


### fiftydeg_sylius_robots_channels_suite.yaml Example

```
sylius_fixtures:
  suites:
    fiftydeg_sylius_robots_plugin_channels_suite:
      listeners:
        fiftydeg_sylius_robots_plugin_channels_listener: null
      fixtures:
        fiftydeg_sylius_robots_plugin_channels_fixture:
          options:
            - code: FASHION_WEB
              hostname: syliusplugin.local
            - name: DISALLOW CHECKOUT
              code: DISALLOW_CHECKOUT
              locales:
                - en_US
              currencies:
                - USD
              hostname: syliusplugindc.local
            - name: DISALLOW CHECKOUT2
              code: DISALLOW_CHECKOUT2
              locales:
                - en_US
              currencies:
                - USD
              hostname: syliusplugindc2.local
            - name: DISALLOW CHECKOUT3
              code: DISALLOW_CHECKOUT3
              locales:
                - en_US
              currencies:
                - USD
              hostname: syliusplugindc3.local
```

## Usage

### Running plugin tests

  - Run `cd .docker && ./bin/start_test.sh` in order to start docker compose in test mode
  - Wait docker to be up and running...
  - Run `cd .docker && ./bin/php_test.sh` in order to start static analysis and Behat tests

#### BDD
A suite for BDD testing is already present; it is registered in `/tests/Behat/Resources/services.yml`, you cand find the features in `/features`, the contexts in `/tests/Behat/Resources/suites.yml`, and the asscoiated PHP code in /tests/Behat/Context/Ui/Shop.
It works on two hidden divs in your project footer; one should be cached and the other one not; but you can modify the test as you wish.

Lastly, you have to add `config/packages/<environmente-where-you-are-working-in>/fiftydeg_sylius_cache_plugin.yaml`, as described before; so you can turn on and off che cache for the two divs in thw twigs specified before.


