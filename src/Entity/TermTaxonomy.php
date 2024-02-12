<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use SymfonyWP\Repositories\TermTaxonomyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermTaxonomyRepository::class)]
#[Wordpress\TableName(name: 'term_taxonomy')]
class TermTaxonomy
{
    #[ORM\Id]
    #[ORM\Column(name: 'term_taxonomy_id', type: 'bigint', nullable: false)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Term::class)]
    #[ORM\JoinColumn(name: 'term_id', referencedColumnName: 'term_id')]
    private ?Term $term = null;

    #[ORM\Column(name: 'taxonomy', type: 'string', length: 32)]
    private ?string $taxonomy = null;

    #[ORM\Column(name: 'description', type: 'text')]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: TermTaxonomy::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent', referencedColumnName: 'term_taxonomy_id')]
    private ?TermTaxonomy $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: TermTaxonomy::class)]
    private ?Collection $children;

    #[ORM\Column(name: 'count', type: 'integer')]
    private ?int $count = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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
    public function getTaxonomy(): ?string
    {
        return $this->taxonomy;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return TermTaxonomy|null
     */
    public function getParent(): ?TermTaxonomy
    {
        return $this->parent;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return array<int, TermTaxonomy>
     */
    public function getChildren(): array
    {
        return $this->children->toArray();
    }

}
