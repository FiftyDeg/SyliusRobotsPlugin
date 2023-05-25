## Installation
------------------

<a href="../README.md" target="_blank">Back</a>

1. Installing with Composer
```
$ composer require fifty-deg/sylius-robots-plugin
```

2. Add `FiftyDeg\SyliusRobotsPlugin\FiftyDegSyliusRobotsPlugin::class => ['all' => true],` into /config/bundles.php 

3. Register routes and vendor settings
In order to register routes, add the following code snippet in `config/routes.yaml`:  
```yaml
fiftydeg_sylius_robots_plugin:
    resource: "@FiftyDegSyliusRobotsPlugin/Resources/config/routes.yaml"
```

4. In `config/services.yaml` remove this bundle from autowiring and register vendor settings:  

```yaml
services:
    App\:
        resource: '../src/*'
        exclude: '../src/{FiftyDeg/Robots,Entity,Migrations,Tests,Kernel.php}'
```

5. Now you are able to use, as described in the next session <a href="./usage.md" target="_blank">Usage</a>