<a href="../README.md" target="_blank">Back</a>

## Installation

1. Install with Composer
```bash
composer require fifty-deg/sylius-robots-plugin
```

2. Add `FiftyDeg\SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true],` into `/config/bundles.php`

3. Register routes and vendor settings by adding the following code snippet in `config/routes.yaml`:  
```yaml
fiftydeg_sylius_robots_plugin:
    resource: "@FiftyDegSyliusRobotsPlugin/Resources/config/routes.yaml"
```

---

<a href="./usage.md" target="_blank">Next: Usage</a>
