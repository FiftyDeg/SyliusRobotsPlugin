<a href="../README.md" target="_blank">Back</a>

## Installation

1. Installing with Composer
```bash
$ composer require fifty-deg/sylius-robots-plugin
```

1. Add `FiftyDeg\SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true],` into `/config/bundles.php`

2. Register routes and vendor settings
In order to register routes, add the following code snippet in `config/routes.yaml`:  
```yaml
fiftydeg_sylius_robots_plugin:
    resource: "@FiftyDegSyliusRobotsPlugin/Resources/config/routes.yaml"
```

---

Next: <a href="./usage.md" target="_blank">Usage</a>
