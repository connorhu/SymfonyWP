<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[Wordpress\TableName(name: 'options')]
class Options
{
    #[ORM\Id]
    #[ORM\Column(name: 'option_id', type: 'bigint', nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'option_name', type: 'string', length: 191, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'option_value', type: 'text', nullable: false, options: ['default' => self::AUTOLOAD_TRUE])]
    private ?string $value = null;

    private const AUTOLOAD_TRUE = 'yes';
    private const AUTOLOAD_FALSE = 'no';

    #[ORM\Column(name: 'autoload', type: 'string', length: 20, nullable: false)]
    private string $autoload = self::AUTOLOAD_TRUE;

    public function __construct()
    {
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function getAutoload(): bool
    {
        return $this->autoload === self::AUTOLOAD_TRUE;
    }
}
