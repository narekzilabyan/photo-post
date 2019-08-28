<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param int $id
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateRating(int $id): void
    {
        // get summery votes
        $result = $this->createQueryBuilder('p')
            ->select('avg(v.vote)')
            ->join('p.votes', 'v')
            ->where('p.id = :post')
            ->setParameter('post', $id)
            ->getQuery()
            ->getSingleScalarResult();

        $post = $this->find($id);

        $em = $this->getEntityManager();
        $post->setRating(intval($result) ?? 0);

        $em->persist($post);
        $em->flush();
    }

    public function findWithFilter($search = null, $rating = null, $date = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p');

        if ($search) {
            $qb->andWhere('p.title LIKE :search')
                ->setParameter('search','%' . $search . '%');
        }

        if ($rating) {
            $qb->andWhere('p.rating = :rating')
                ->setParameter('rating', $rating);
        }

        if ($date) {
            $qb->andWhere('p.date = :date')
                ->setParameter('date', $date);
        }

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
