<?php

namespace AppBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
        ->where('u.username = :username OR u.email = :email')
        ->setParameter('username', $username)
        ->setParameter('email', $username)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function findActivesUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleteAt is NULL')
            ->andWhere('u.disableAt is NULL')
            ->getQuery()
            ->getResult();
    }

    public function findInactivesUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleteAt is NOT NULL or u.disableAt is NOT NULL')
            ->orderBy('u.deleteAt')
            ->getQuery()
            ->getResult();
    }


    public function findDeletedUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleteAt is NOT NULL')
            ->getQuery()
            ->getResult();
    }

    public function filterUsers($search)
    {

        return $this->createQueryBuilder('u')
            ->where('
                u.firstname LIKE :search OR
                u.lastname LIKE :search OR
                u.postalCode LIKE :search OR
                u.address LIKE :search OR
                u.city LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();
    }
}
