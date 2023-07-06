<?php

namespace App\Repository;

use App\Entity\Camera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Camera>
 *
 * @method Camera|null find($id, $lockMode = null, $lockVersion = null)
 * @method Camera|null findOneBy(array $criteria, array $orderBy = null)
 * @method Camera[]    findAll()
 * @method Camera[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CameraRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Camera::class);
    }
}
