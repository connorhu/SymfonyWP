<?php

namespace SymfonyWP\Repositories;

use SymfonyWP\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPages(): array
    {
        return $this->findBy(['type' => 'page']);
    }

    public function findAllRevisions(Post $post): array
    {
        return $this->findBy(['parent' => $post, 'type' => 'revision']);
    }

    /**
     * @param Post $post
     * @return array<int, Post>
     */
    public function findAttachmentWithPost(Post $post): array
    {
        return $this->findBy([
            'type' => 'attachment',
            'parent' => $post
        ]);
    }
}
