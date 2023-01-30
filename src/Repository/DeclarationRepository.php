<?php

namespace App\Repository;

use App\Entity\Declaration;
use App\Models\DeclarationSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Declaration>
 *
 * @method Declaration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Declaration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Declaration[]    findAll()
 * @method Declaration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeclarationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Declaration::class);
    }

    public function save(Declaration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Declaration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function searchDeclaration(DeclarationSearch $declarationSearch, UserInterface $user = null): Query
    {
        $query = $this->getDocuments($declarationSearch, $user);
        return $query->getQuery();

    }

    private function getDocuments(DeclarationSearch $declarationSearch, UserInterface $user = null): QueryBuilder
    {
        $qb = $this->getOrderBycreatedAtDesc() ;
        if($declarationSearch->getQuery()){
            $qb->leftJoin('d.document', 'doc')
                ->andWhere('doc.owner LIKE  :query')
                ->setParameter('query', "%{$declarationSearch->getQuery()}%")
                ->orderBy('doc.owner', 'ASC')
            ;
        }
        if($user){
            return $this->getCurrentUser($qb, $user);
        }
        return $qb;
    }

    private function getCurrentUser(QueryBuilder $qb, UserInterface $user): QueryBuilder
    {
        $qb->leftJoin('d.user', 'u')
            ->andWhere($qb->expr()->eq('u.id', ':userId'))
            ->setParameter('userId', $user->getId())
        ;
        return $qb;
    }
    private function getOrderByCreatedAtDesc(): QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'DESC')
            ;
    }
//    /**
//     * @return Declaration[] Returns an array of Declaration objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Declaration
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
