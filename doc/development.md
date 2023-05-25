## Development
------------------

<a href="../README.md" target="_blank">Back</a>

### Docker
------------------

1. Execute `cd ./.docker && ./bin/start_dev.sh`

2. Configure `/etc/hosts` adding the line `127.0.0.1    syliusplugin.local`

3. Add `FiftyDeg\SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true],` into /config/bundles.php 

4. In /config/packages/dev/fifty_deg_sylius_robots.yaml insert your robots configurations, detailed per channel.

5. In case you wish to develop with more channels working on different hostnames, remember to add all the channel hostname that you need into docker-compose.yml, under `extra_hosts`, specifying also the exact port. For example `my-new-channel-hostname:127.0.0.1`


### In case you need some extra channel for developing, you could add any automatically with a Fixture

1. In /config/packages/dev/fiftydeg_sylius_robots_channels_suite.yaml, you can add all the channels you need automatically, with a Fixture.

2. Add the line `&& yes | php bin/console sylius:install --no-interaction --verbose --env ${APP_ENV}` in docker-compose.yml, after the line `&& yes | php bin/console sylius:install:check-requirements`, where ${APP_ENV} should be replace with the environment you are using right now, i.e. `dev`
and the line `&& yes | php bin/console sylius:fixtures:load fiftydeg_sylius_robots_plugin_channels_suite --no-interaction` after `&& yes | php bin/console sylius:fixtures:load default --no-interaction`

<br/>

### Example - fiftydeg_sylius_robots_channels_suite.yaml

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