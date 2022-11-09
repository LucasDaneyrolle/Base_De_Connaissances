<?php

namespace App\Repository;

use App\Entity\Form;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Form>
 *
 * @method Form|null find($id, $lockMode = null, $lockVersion = null)
 * @method Form|null findOneBy(array $criteria, array $orderBy = null)
 * @method Form[]    findAll()
 * @method Form[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Form::class);
    }

    public function add(Form $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Form $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array of form from search
     */
    public function findBySearch(string $search):array {


        $query = $this->createQueryBuilder('f')
            ->innerJoin('f.categoryForm', 'cf')
            ->where("f.title like '%$search%'")
            ->orWhere("f.problem like '%$search%'")
            ->orWhere("f.solution like '%$search%'");

        return $query->getQuery()->getResult();
    }

    /**
     * @return array of form category
    */
    public function findAllCategory(int $id):array {
        $query = $this->createQueryBuilder('f')
            ->select('cf.id, cf.libelle')
            ->innerJoin('f.categoryForm', 'cf')
            ->where('f.id = :id')
            ->setParameter(':id', $id);

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Form[] Returns an array of Form objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Form
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
