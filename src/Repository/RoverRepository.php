<?php

namespace App\Repository;

use App\Entity\Rover;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rover>
 *
 * @method Rover|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rover|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rover[]    findAll()
 * @method Rover[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoverRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rover::class);
    }

    public function findRoversByYear(int $year): array
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->lte('r.min_date', ':firstYearDay'),
                $queryBuilder->expr()->gte('r.max_date', ':firstYearDay')
            ))
            ->orWhere($queryBuilder->expr()->andX(
                $queryBuilder->expr()->lte('r.min_date', ':lastYearDay'),
                $queryBuilder->expr()->gte('r.max_date', ':lastYearDay')
            ))
            ->setParameter('firstYearDay', $year . '-01-01')
            ->setParameter('lastYearDay', $year . '-12-31');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

}
