<?php

namespace SymfonyWP\Repositories;

use SymfonyWP\Entity\Attachment;
use SymfonyWP\Entity\Post;
use SymfonyWP\Entity\PostMeta;
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


    public function findFeaturedImageForPost(Post $post): ?Attachment
    {
        $featuredImageId = $post->getFeaturedImageId();

        if ($featuredImageId === null) {
            return null;
        }

        return $this->find($featuredImageId);
    }

    /**
     * Resolves featured images for the provided posts with a single query to avoid
     * N+1 lookups in listing views.
     *
     * @param array<int, Post> $posts
     * @return array<int, Attachment>
     */
    public function findFeaturedImagesForPosts(array $posts): array
    {
        $postIds = array_values(array_filter(array_map(static function (Post $post): ?int {
            return $post->getId();
        }, $posts)));

        if ($postIds === []) {
            return [];
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $rows = $queryBuilder
            ->select('IDENTITY(pm.post) AS postId', 'a')
            ->from(PostMeta::class, 'pm')
            ->innerJoin(Attachment::class, 'a', 'WITH', 'a.id = pm.value')
            ->where('pm.key = :thumbnailKey')
            ->andWhere($queryBuilder->expr()->in('IDENTITY(pm.post)', ':postIds'))
            ->andWhere('a.type = :attachmentType')
            ->setParameter('thumbnailKey', '_thumbnail_id')
            ->setParameter('postIds', $postIds)
            ->setParameter('attachmentType', 'attachment')
            ->getQuery()
            ->getResult();

        $featuredImagesByPostId = [];
        foreach ($rows as $row) {
            $featuredImagesByPostId[(int) $row['postId']] = $row[0];
        }

        return $featuredImagesByPostId;
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
