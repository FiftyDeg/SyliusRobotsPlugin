<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class FiftyDegSyliusRobotsExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var ConfigurationInterface $containerConf */
        $containerConf = $this->getConfiguration([], $container);

        /** @var array<string, string> $config */
        $config = $this->processConfiguration($containerConf, $configs);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $fileLocator);
        $loader->load('config_bundle.yaml');

        foreach ($config as $key => $param) {
            $container->setParameter($key, $param);
        }
    }
}
