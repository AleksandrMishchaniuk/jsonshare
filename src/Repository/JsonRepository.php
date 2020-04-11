<?php

namespace App\Repository;

use App\Entity\Json;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Json|null find($id, $lockMode = null, $lockVersion = null)
 * @method Json|null findOneBy(array $criteria, array $orderBy = null)
 * @method Json[]    findAll()
 * @method Json[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JsonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Json::class);
    }

    /**
     * @param int $id
     * @param string $hash
     * @return Json|null
     */
    public function findByIdAndHash(int $id, string $hash): ?Json
    {
        return $this->createQueryBuilder('j')
            ->innerJoin('j.hashes', 'h')
            ->andWhere('j.id = :id')
            ->andWhere('h.hash = :hash')
            ->setParameters([
                'id' => $id,
                'hash' => $hash
            ])->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Json[] Returns an array of Json objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Json
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
