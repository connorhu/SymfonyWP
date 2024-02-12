<?php

namespace SymfonyWP;

use SymfonyWP\Mapping\TableName;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;

class MultisiteNamingStrategy extends DefaultNamingStrategy
{
    public function __construct(
        private readonly MultisiteProvider $multisiteProvider,
        private readonly string $sitePrefix
    ) {
    }

    public function classToTableName($className): string
    {
        $reflection = new \ReflectionClass($className);
        $attributes = $reflection->getAttributes(TableName::class);

        if (count($attributes) === 0) {
            return parent::classToTableName($className);
        }

        $tableName = $attributes[0]->getArguments()['name'];

        $multisiteNumber = $this->multisiteProvider->getMultisiteNumber();
        $multisitePrefix = $multisiteNumber <= 1 ? '' : sprintf('%d_', $multisiteNumber);
        return $this->sitePrefix . $multisitePrefix . $tableName;
    }
}
