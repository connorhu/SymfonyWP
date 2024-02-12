<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[Wordpress\TableName(name: 'postmeta')]
class PostMeta
{
    #[ORM\Id]
    #[ORM\Column(name: 'meta_id', type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'metas')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'ID')]
    private ?Post $post = null;

    #[ORM\Column(name: 'meta_key', type: 'string', length: 255)]
    protected ?string $key = null;

    #[ORM\Column(name: 'meta_value', type: 'text')]
    protected ?string $value = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
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

    public function getUnserializedValue(): mixed
    {
        return $this->value !== null ? unserialize($this->value) : null;
    }
}
