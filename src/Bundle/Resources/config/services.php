<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use SymfonyWP\AttachmentPathResolver;
use SymfonyWP\MultisiteNamingStrategy;
use SymfonyWP\MultisiteProvider;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('SymfonyWP\\Repositories\\', __DIR__ . '/../../../Repositories/*')
        ->tag('doctrine.repository_service')
        ->public();

    $services->load('SymfonyWP\\Entity\\', __DIR__ . '/../../../Entity/*')
        ->public();

    $services->set(MultisiteProvider::class)
        ->public();

    $services->set(AttachmentPathResolver::class)
        ->args(['%symfony_wp.wp_installation_path%'])
        ->public();

    $services->set(MultisiteNamingStrategy::class)
        ->args([
            service(MultisiteProvider::class),
            '%symfony_wp.site_prefix%',
        ])
        ->public();
};
