<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


/**
 *
 * @author plfort - Cogipix
 *
 */
class UserRepository extends EntityRepository{



    public function getUserDetails($currentUser,$id){
        $qb= $this->createQueryBuilder('u');
        $qb->select('u');
        $qb->addSelect('(CASE WHEN u IN (SELECT IDENTITY(ml.toUser) FROM CogimixCommonBundle:Listener ml WHERE ml.fromUser = :user) THEN 1 ELSE 0 END) as added');
        $qb->andWhere('u.id = :userId AND u.id != :user');
        $qb->leftJoin('u.listeners','l',Join::WITH,'l.fromUser = :user');
        $qb->andWhere('l IS NULL OR l.accepted = 1');
        $qb->setParameter('user', $currentUser->getId());
        $qb->setParameter('userId', $id);
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        try{
            return $query->getSingleResult();
        }catch(\NoResultException $ex){
            return null;
        }
    }

    public function findByUsernameLike($currentUser,$username){
       $qb= $this->createQueryBuilder('u');
       $qb->select('u');
       $qb->addSelect('(CASE WHEN u IN (SELECT IDENTITY(ml.toUser) FROM CogimixCommonBundle:Listener ml WHERE ml.fromUser = :user) THEN 1 ELSE 0 END) as added');
       $qb->where($qb->expr()->like('u.username',$qb->expr()->literal($username.'%')));
       $qb->andWhere('u.id != :user');
       $qb->leftJoin('u.listeners','l',Join::WITH,'l.fromUser = :user');
       $qb->andWhere('l IS NULL OR l.accepted = 1');
       $qb->setParameter('user', $currentUser->getId());
       $query=$qb->getQuery();
       $query->useQueryCache(true);
       return $query->getResult();
    }

    public function getListeningUsers($currentUser){
        $qb= $this->createQueryBuilder('u');
        $qb->join('u.listeners','l',Join::WITH,'l.fromUser = :currentUser');

        $qb->setParameter('currentUser', $currentUser);
        $qb->andWhere('l.accepted = 1');
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult();
    }

    public function getListenerUsers($currentUser){
        $qb= $this->createQueryBuilder('u');
        $qb->select('u','l.accepted');
        $qb->join('u.myListenings','l',Join::WITH,'l.toUser = :currentUser');
        $qb->setParameter('currentUser', $currentUser);
        $query=$qb->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }

}