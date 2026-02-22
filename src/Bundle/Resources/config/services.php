<?php

use SymfonyWP\MultisiteNamingStrategy;
use SymfonyWP\MultisiteProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('SymfonyWP\\Repositories\\', __DIR__ . '/../../../Repositories/*')
        ->public();

    $services->load('SymfonyWP\\Entity\\', __DIR__ . '/../../../Entity/*')
        ->public();

    $services->set(MultisiteProvider::class)
        ->public();

    $services->set(MultisiteNamingStrategy::class)
        ->args([
            service(MultisiteProvider::class),
            '%symfony_wp.site_prefix%',
        ])
        ->public();
};
