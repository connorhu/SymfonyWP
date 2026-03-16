<?php

namespace SymfonyWP\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class SymfonyWPExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('doctrine')) {
            return;
        }

        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (($config['entity_manager'] ?? null) !== null) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'entity_managers' => [
                    'wordpress' => [
                        'connection' => $config['connection'] ?? 'default',
                        'naming_strategy' => 'SymfonyWP\\MultisiteNamingStrategy',
                        'mappings' => [
                            'SymfonyWP' => [
                                'is_bundle' => false,
                                'type' => 'attribute',
                                'dir' => realpath(__DIR__ . '/../../Entity'),
                                'prefix' => 'SymfonyWP\\Entity',
                                'alias' => 'SymfonyWP',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('symfony_wp.wp_installation_path', $config['wp_installation_path']);
        $container->setParameter('symfony_wp.site_prefix', $config['site_prefix']);
        $container->setParameter('symfony_wp.connection', $config['connection']);
        $container->setParameter('symfony_wp.entity_manager', $config['entity_manager'] ?? 'wordpress');

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
    }
}
