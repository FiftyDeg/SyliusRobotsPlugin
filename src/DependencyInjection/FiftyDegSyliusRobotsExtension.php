<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class FiftyDegSyliusRobotsExtension extends AbstractResourceExtension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);

        $fileLocator = new FileLocator(__DIR__ . "/../Resources/config");
        $loader = new YamlFileLoader($container, $fileLocator);
        $loader->load('config_bundle.yaml');

        foreach ($config as $key => $param) {
            $container->setParameter($key, $param);
        }
    }
}
