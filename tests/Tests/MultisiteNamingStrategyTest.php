<?php

namespace SymfonyWP\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use SymfonyWP\MultisiteNamingStrategy;
use SymfonyWP\MultisiteProvider;
use PHPUnit\Framework\TestCase;

class MultisiteNamingStrategyTest extends TestCase
{
    #[DataProvider(methodName: 'classToTableNameDataProvider')]
    public function testClassToTableName(string $className, MultisiteProvider $provider, string $tableName)
    {
        $namingStrategy = new MultisiteNamingStrategy($provider, 'ez_');

        $this->assertSame($tableName, $namingStrategy->classToTableName($className));
    }

    public static function classToTableNameDataProvider(): \Generator
    {
        $provider = new MultisiteProvider();
        $provider->setMultisiteNumber(1);
        yield ['SymfonyWP\Entity\Options', $provider, 'ez_options'];

        $provider = new MultisiteProvider();
        $provider->setMultisiteNumber(0);
        yield ['SymfonyWP\Entity\Options', $provider, 'ez_options'];

        $provider = new MultisiteProvider();
        $provider->setMultisiteNumber(99);
        yield ['SymfonyWP\Entity\Options', $provider, 'ez_99_options'];
    }
}
