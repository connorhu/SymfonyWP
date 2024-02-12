<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use SymfonyWP\Repositories\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Wordpress\TableName(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: 'ID', type: 'bigint', nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'user_login', type: 'string', length: 60, nullable: false)]
    private ?string $login = null;

    #[ORM\Column(name: 'user_pass', type: 'string', length: 255, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(name: 'user_nicename', type: 'string', length: 50, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'display_name', type: 'string', length: 250, nullable: false)]
    private ?string $displayName = null;

    #[ORM\Column(name: 'user_status', type: 'integer', nullable: false, options: ['default' => 0])]
    private int $status = 0;

    #[ORM\Column(name: 'spam', type: 'boolean', nullable: false, options: ['default' => false])]
    private bool $spam = false;

    #[ORM\Column(name: 'deleted', type: 'boolean', nullable: false, options: ['default' => false])]
    private bool $deleted = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserMeta::class)]
    private ?Collection $meta;

    public function __construct()
    {
        $this->meta = new ArrayCollection();
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
    public function getLogin(): ?string
    {
        return $this->login;
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
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isSpam(): bool
    {
        return $this->spam;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return array<int, UserMeta>
     */
    public function getMeta(): array
    {
        return $this->meta->toArray();
    }
}
