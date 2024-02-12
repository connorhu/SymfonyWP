<?php

namespace SymfonyWP\Mapping;

use Doctrine\ORM\Mapping\MappingAttribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class TableName implements MappingAttribute
{
    public ?string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
