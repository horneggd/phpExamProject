<?php

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Exam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exam[]    findAll()
 * @method Exam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exam::class);
    }

    public function getExamWithCategories(array $categoryNames)
    {
      $qb = $this->createQueryBuilder('a');
  
      // On fait une jointure avec l'entité Category avec pour alias « c »
      $qb
        ->innerJoin('a.categories', 'c')
        ->addSelect('c')
      ;
  
      // Puis on filtre sur le nom des catégories à l'aide d'un IN
      $qb->where($qb->expr()->in('c.name', $categoryNames));
      // La syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine
  
      // Enfin, on retourne le résultat
      return $qb
        ->getQuery()
        ->getResult()
      ;
    }
//    /**
//     * @return Exam[] Returns an array of Exam objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Exam
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
