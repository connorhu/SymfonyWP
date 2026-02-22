<?php

namespace SymfonyWP\Bundle\DependencyInjection;

use SymfonyWP\MultisiteNamingStrategy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\FileLocator;

class SymfonyWPExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('symfony_wp.wp_installation_path', $config['wp_installation_path']);
        $container->setParameter('symfony_wp.site_prefix', $config['site_prefix']);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('doctrine')) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'entity_managers' => [
                    'default' => [
                        'naming_strategy' => MultisiteNamingStrategy::class,
                        'mappings' => [
                            'SymfonyWP' => [
                                'is_bundle' => false,
                                'type' => 'attribute',
                                'dir' => __DIR__ . '/../../Entity',
                                'prefix' => 'SymfonyWP\\Entity',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
