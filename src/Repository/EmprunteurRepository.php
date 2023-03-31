<?php

namespace App\Repository;

use App\Entity\Emprunteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunteur>
 *
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }

    public function save(Emprunteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Emprunteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findAllEmprunteurs(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
    */
    public function findEmprunteurByUserId(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.user', 'u')
            ->andWhere('e.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Emprunteur[] Returns an array of Emprunteur objects
    */
    public function findByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :keyword OR e.prenom LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Emprunteur[] Returns an array of Emprunteur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Emprunteur
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
