<?php

namespace App\Repository;

use App\DTO\HolidayParamsInput;
use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photo>
 *
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    public function save(Photo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Photo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByParams(HolidayParamsInput $params): array
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Photo::class, 'p')
            ->leftJoin('p.rover', 'r')
            ->leftJoin('p.camera', 'c')
            ->orderBy('p.id', 'ASC');

        if ($params->rover) {
            $queryBuilder->andWhere('LOWER(r.name) = :rover')
                ->setParameter('rover', $params->rover);
        }

        if ($params->camera) {
            $queryBuilder->andWhere('LOWER(c.name) = :camera')
                ->setParameter('camera', $params->camera);
        }

        if ($params->start_date && $params->end_date) {
            $queryBuilder->andWhere($queryBuilder->expr()->andX(
                    $queryBuilder->expr()->gte('p.date', ':start'),
                    $queryBuilder->expr()->lte('p.date', ':end')
                ))
                ->setParameter('start', $params->start_date)
                ->setParameter('end',$params->end_date);
        }

        if ($params->date) {
            $queryBuilder->andWhere('p.date = :date')
                ->setParameter('date', $params->date);
        }

        return $queryBuilder->getQuery()->getResult();
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
