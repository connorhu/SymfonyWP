<?php

namespace SymfonyWP\Repositories;

use SymfonyWP\Entity\TermTaxonomy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TermTaxonomy|null find($id, $lockMode = null, $lockVersion = null)
 * @method TermTaxonomy|null findOneBy(array $criteria, array $orderBy = null)
 * @method TermTaxonomy[]    findAll()
 * @method TermTaxonomy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermTaxonomyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermTaxonomy::class);
    }
}
