<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[Wordpress\TableName(name: 'term_relationships')]
class TermRelationship
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: TermTaxonomy::class)]
    #[ORM\JoinColumn(name: 'term_taxonomy_id', referencedColumnName: 'term_taxonomy_id', nullable: false)]
    private ?TermTaxonomy $termTaxonomy = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(name: 'object_id', referencedColumnName: 'ID', nullable: false)]
    private ?Post $post = null;

    #[ORM\Column(name: 'term_order', type: 'integer', nullable: false)]
    private ?int $order;

    /**
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @return TermTaxonomy|null
     */
    public function getTermTaxonomy(): ?TermTaxonomy
    {
        return $this->termTaxonomy;
    }

    /**
     * @return int|null
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }
}
