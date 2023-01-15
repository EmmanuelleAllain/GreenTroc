<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\ItemToBorrow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemToBorrow>
 *
 * @method ItemToBorrow|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemToBorrow|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemToBorrow[]    findAll()
 * @method ItemToBorrow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemToBorrowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemToBorrow::class);
    }

    public function add(ItemToBorrow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ItemToBorrow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findIdsItemByKeyword(string $keyword): mixed
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->where('i.name LIKE :name')
            ->setParameter('name', '%' . $keyword . '%')
            ->getQuery();

        $itemToBorrow = $queryBuilder->getResult();
        $idsArray = [];
        foreach ($itemToBorrow as $item) {
            $id = $item->getId();
            $idsArray[] = $id;
        }
        return $idsArray;
    }

//    /**
//     * @return ItemToBorrow[] Returns an array of ItemToBorrow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ItemToBorrow
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
