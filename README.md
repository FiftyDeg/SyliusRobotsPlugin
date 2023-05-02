<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Sylius Robots Plugin</h1>

<p align="center">A Sylius plugin that allows configuring different sitemaps per each channel.</p>

## Documentation

For a comprehensive guide on Sylius Plugins development please go to Sylius documentation,
there you will find the <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development Guide</a>, that is full of examples.

## Quickstart Installation

### Docker

1. Execute `cd ./.docker && ./bin/start_dev.sh`
2. Configure `/etc/hosts` and add the `127.0.0.1    syliusplugin.local` new entry
3. Add `FiftyDeg\SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true],` into /config/bundles.php 
4. Add `- { resource: "@FiftyDegSyliusRobotsPlugin/Resources/config/config_vendor.yaml" }` into /config/services.yaml
5. In /config/packages/dev/fifty_deg_sylius_robots.yaml insert your robots configurations, detailed per channel.
6. In /config/packages/dev/fiftydeg_sylius_robots_channels_suite.yaml, you can add all the channels you need automatically, with a Fixture.
7. Open your browser and go to `https://syliusplugin.local`

### fiftydeg_sylius_robots_channels_suite.yaml Example

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

## Usage

### Running plugin tests

  - Run `cd .docker && ./bin/start_test.sh` in order to start docker compose in test mode
  - Wait docker to be up and running...
  - Run `cd .docker && ./bin/php_test.sh` in order to start static analysis and Behat tests
