<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\GithubResourceOwner;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findFromGithubOauth(GithubResourceOwner $owner) :User
    {

        $user = $this->createQueryBuilder('u')
            ->where('u.github_id =:github_id')
            ->setParameters([
                'github_id' => $owner->getId()
            ])
            ->getQuery()
            ->getOneOrNullResult();

        if($user ){
            return $user;
        }

        $user =(new User())
            ->setGithubId($owner->getId())
            ->setEmail($owner->getEmail())
            ->setNom("Mohamed")
            ->setPrenom("Rahal")
            ->setUsername($owner->getNickname())
            ->setDateNaissance(new \DateTime())
            ->setSexe('male')
            ->setTel('21541201')
            ->setAdresse('rue abdallah ibn zamaa')
            ->setImage('dede');
        $em = $this->getEntityManager();
        //$em->persist($user);
        //$em->flush();
        return $user;



    }
}
