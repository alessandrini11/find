<?php

namespace App\Repository;

use App\Entity\Document;
use App\Models\DocumentSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function save(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchDocuments(DocumentSearch $documentSearch, UserInterface $user = null): Query
    {
        $query = $this->getDocuments($documentSearch, $user);
        return $query->getQuery();

    }

    private function getDocuments(DocumentSearch $documentSearch, UserInterface $user = null): QueryBuilder
    {
        $qb = $user ? $this->getOrderBycreatedAtDesc() : $this->getOrderByOwnerAsc();
        if($documentSearch->getQuery()){
            $qb->andWhere('d.owner LIKE :query')
                ->orWhere('d.idNumber LIKE :query')
                ->setParameter('query', "%{$documentSearch->getQuery()}%");
        }
//        dd($user);
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
    private function getOrderByOwnerAsc(): QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.owner', 'ASC')
            ;
    }
//    /**
//     * @return Document[] Returns an array of Document objects
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

//    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
