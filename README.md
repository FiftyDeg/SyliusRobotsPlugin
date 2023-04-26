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
2. Open your browser and go to `https://syliusplugin.local`

## Usage

### Running plugin tests

  - Run `cd .docker && ./bin/start_test.sh` in order to start docker compose in test mode
  - Wait docker to be up and running...
  - Run `cd .docker && ./bin/php_test.sh` in order to start static analysis and Behat tests
