<?php

namespace SymfonyWP\Entity;

use SymfonyWP\Mapping as Wordpress;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[Wordpress\TableName(name: 'users')]
class UserMeta
{
    #[ORM\Id]
    #[ORM\Column(name: 'umeta_id', type: 'bigint', nullable: false)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'meta')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'ID')]
    private ?User $user = null;

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
}
