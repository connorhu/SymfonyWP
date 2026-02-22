<?php

namespace SymfonyWP\Repositories;

use SymfonyWP\Entity\Attachment;
use SymfonyWP\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attachment[]    findAll()
 */
class AttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attachment::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Attachment
    {
        $attachment = parent::find($id, $lockMode, $lockVersion);

        if ($attachment === null || $attachment->getType() !== 'attachment') {
            return null;
        }

        return $attachment;
    }

    /**
     * @param array<string, mixed>|null $orderBy
     * @return array<int, Attachment>
     */
    public function findAll(?array $orderBy = null): array
    {
        return $this->findBy([], $orderBy);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, mixed>|null $orderBy
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?Attachment
    {
        return parent::findOneBy($this->withAttachmentCriteria($criteria), $orderBy);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, mixed>|null $orderBy
     * @return array<int, Attachment>
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($this->withAttachmentCriteria($criteria), $orderBy, $limit, $offset);
    }


    /**
     * @return array<int, Attachment>
     */
    public function findAllByPost(Post $post): array
    {
        return $this->findBy(['parent' => $post]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @return array<string, mixed>
     */
    private function withAttachmentCriteria(array $criteria): array
    {
        if (array_key_exists('type', $criteria)) {
            return $criteria;
        }

        $criteria['type'] = 'attachment';

        return $criteria;
    }
}
