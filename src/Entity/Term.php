<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[Wordpress\TableName(name: 'terms')]
class Term
{
    #[ORM\Id]
    #[ORM\Column(name: 'term_id', type: 'bigint', nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 200)]
    private ?string $name = null;

    #[ORM\Column(name: 'slug', type: 'string', length: 200)]
    private ?string $slug = null;

    // term_group

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
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
