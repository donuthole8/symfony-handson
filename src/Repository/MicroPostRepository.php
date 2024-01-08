<?php

namespace App\Repository;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MicroPost>
 *
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function save(MicroPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MicroPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithComments(): array
    {
        return $this->findAllQuery(withComments: true)
            ->getQuery()
            ->getResult();
    }

    public function findAllByAuthor(int|User $author): array
    {
        return $this->findAllQuery(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )
            ->where('mp.author = :author')
            ->setParameter('author', $author instanceof User ? $author->getId() : $author)
            ->getQuery()
            ->getResult()
        ;
    }

    private function findAllQuery(
        bool $withComments = false,
        bool $withLikes = false,
        bool $withAuthors = false,
        bool $withProfiles = false,
    ): QueryBuilder
    {
        $query = $this->createQueryBuilder('mp');

        if ($withComments) {
            $query->leftJoin('mp.comments', 'c')->addSelect('c');
        }

        if ($withLikes) {
            $query->leftJoin('mp.likedBy', 'l')->addSelect('l');
        }

        if ($withAuthors || $withProfiles) {
            $query->leftJoin('mp.author', 'a')->addSelect('a');
        }

        if ($withProfiles) {
            $query->leftJoin('a.userProfile', 'up')->addSelect('up');
        }

        return $query->orderBy('mp.created', 'DESC');
    }
}
