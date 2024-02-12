<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[Wordpress\TableName(name: 'termmeta')]
class TermMeta
{
    #[ORM\Id]
    #[ORM\Column(name: 'meta_id', type: 'bigint', nullable: false)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Term::class)]
    #[ORM\JoinColumn(referencedColumnName: 'term_id', fieldName: 'term_id')]
    private ?Term $term = null;

    // meta_key
    #[ORM\Column(name: 'meta_key', type: 'string', length: 255, nullable: true)]
    private ?string $key = null;

    // meta_value
    #[ORM\Column(name: 'meta_value', type: 'text', nullable: true)]
    private ?string $value = null;

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
     * @return Term|null
     */
    public function getTerm(): ?Term
    {
        return $this->term;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
}
