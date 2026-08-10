<?php

namespace App\Repository;

use App\Entity\Presentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Presentation>
 */
class PresentationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presentation::class);
    }

/**
 * @return Presentation[]
 */
public function findByOwner(User $owner): array
{
        $qb = $this->createQueryBuilder('p')
        ->where('p.owner = :owner')
        ->setParameter('owner', $owner)
        ->orderBy('p.createdAt', 'DESC');

        return $qb->getQuery()->getResult();

}
}
