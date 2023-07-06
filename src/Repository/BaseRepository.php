<?php

namespace App\Repository;


use App\Entity\Camera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;

class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function save($entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove($entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveAll($entities): void
    {
        foreach ($entities as $entity) {
            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush();
    }

    public function removeAll(): void
    {
        $entityManager = $this->getEntityManager();
        $entities = $this->findAll();

        foreach ($entities as $entity) {
            $entityManager->remove($entity);
        }

        $entityManager->flush();
    }
}