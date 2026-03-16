<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use SymfonyWP\Repositories\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'post_type', type: 'string')]
#[ORM\DiscriminatorMap([
    'post' => Post::class,
    'attachment' => Attachment::class,
])]
#[Wordpress\TableName(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\Column(name: 'ID', type: 'bigint', nullable: false)]
    private ?int $id = null;

    // post_author
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'post_author', referencedColumnName: 'ID', fieldName: 'post_author')]
    private ?User $author = null;

    #[ORM\Column(name: 'post_date', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $postDate = null;

    #[ORM\Column(name: 'post_date_gmt', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $postDateGMT = null;

    #[ORM\Column(name: 'post_content', type: 'text', nullable: false)]
    private ?string $content = null;

    #[ORM\Column(name: 'post_title', type: 'text', nullable: false)]
    private ?string $title = null;

    #[ORM\Column(name: 'post_excerpt', type: 'text', nullable: false)]
    private ?string $excerpt = null;

    #[ORM\Column(name: 'post_status', type: 'string', length: 20, nullable: false)]
    private ?string $postStatus = null;

    #[ORM\Column(name: 'comment_status', type: 'string', length: 20, nullable: false)]
    private ?string $commentStatus = null;

    #[ORM\Column(name: 'ping_status', type: 'string', length: 20, nullable: false)]
    private ?string $pingStatus = null;

    #[ORM\Column(name: 'post_password', type: 'string', length: 255, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(name: 'post_name', type: 'string', length: 200, nullable: false)]
    private ?string $name = null;

    // to_ping

    // pinged

    #[ORM\Column(name: 'post_modified', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\Column(name: 'post_modified_gmt', type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $modifiedAtGMT = null;

    #[ORM\Column(name: 'post_content_filtered', type: 'text', nullable: false)]
    private ?string $contentFiltered = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'childs')]
    #[ORM\JoinColumn(name: 'post_parent', referencedColumnName: 'ID')]
    protected ?Post $parent = null;

    #[ORM\Column(name: 'guid', type: 'string', length: 255, nullable: false)]
    protected ?string $guid = null;

    #[ORM\Column(name: 'menu_order', type: 'integer', nullable: false, options: ['default' => 0])]
    protected ?int $menuOrder = 0;

    #[ORM\Column(name: 'post_type', type: 'string', length: 20, nullable: false)]
    protected ?string $type = null;

    #[ORM\Column(name: 'post_mime_type', type: 'string', length: 100, nullable: false)]
    protected ?string $mimeType = null;

    #[ORM\Column(name: 'comment_count', type: 'bigint', nullable: false)]
    protected ?int $commentCount = 0;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostMeta::class)]
    protected Collection $metas;

    /**
     * @var Collection<int, TermRelationship>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: TermRelationship::class)]
    #[ORM\OrderBy(value: ['order' => 'asc'])]
    protected Collection $termRelationShips;

    /**
     * @return array<int, TermRelationship>
     */
    public function getTermRelationShips(): array
    {
        return $this->termRelationShips->toArray();
    }

    /**
     * @return array<int, TermTaxonomy>
     */
    public function getTermTaxonomiesWithTaxonomy(string $taxonomy): array
    {
        $buffer = [];
        foreach ($this->termRelationShips as $termRelationShip) {
            if ($termRelationShip->getTermTaxonomy()->getTaxonomy() === $taxonomy) {
                $buffer[] = $termRelationShip->getTermTaxonomy();
            }
        }

        return $buffer;
    }

    public function __construct()
    {
        $this->metas = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPostDate(): ?\DateTimeImmutable
    {
        return $this->postDate;
    }

    /**
     * @param \DateTimeImmutable|null $postDate
     */
    public function setPostDate(?\DateTimeImmutable $postDate): void
    {
        $this->postDate = $postDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPostDateGMT(): ?\DateTimeImmutable
    {
        return $this->postDateGMT;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    /**
     * @return string|null
     */
    public function getPostStatus(): ?string
    {
        return $this->postStatus;
    }

    /**
     * @return string|null
     */
    public function getCommentStatus(): ?string
    {
        return $this->commentStatus;
    }

    /**
     * @return string|null
     */
    public function getPingStatus(): ?string
    {
        return $this->pingStatus;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Post|null
     */
    public function getParent(): ?Post
    {
        return $this->parent;
    }

    /**
     * @return string|null
     */
    public function getGuid(): ?string
    {
        return $this->guid;
    }

    /**
     * @return array<int, PostMeta>
     */
    public function getMetas(): array
    {
        return $this->metas->toArray();
    }

    /**
     * @return array<int, string>
     */
    public function getAllMetaKey(): array
    {
        return $this->metas->map(function (PostMeta $postMeta) {
            return $postMeta->getKey();
        })->toArray();
    }

    public function hasMeta(string $key): bool
    {
        return $this->getFirstPostMetaWithKey($key) !== null;
    }

    public function getFirstPostMetaWithKey(string $key): ?PostMeta
    {
        foreach ($this->metas as $meta) {
            if ($meta->getKey() === $key) {
                return $meta;
            }
        }

        return null;
    }

    /**
     * @param string $key
     * @return array<int, PostMeta>
     */
    public function getAllPostMetaWithKey(string $key): array
    {
        $buffer = [];
        foreach ($this->metas as $meta) {
            if ($meta->getKey() === $key) {
                $buffer[] = $meta;
            }
        }

        return $buffer;
    }
}
