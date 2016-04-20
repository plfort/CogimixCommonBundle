<?php


namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

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
        $qb->leftJoin('u.listeners','l',Join::WITH,'l.fromUser = :user AND l.accepted=1');
  
        $qb->setParameter('user', $currentUser->getId());
        $qb->setParameter('userId', $id);
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        try{
            return $query->getSingleResult();
        }catch(NoResultException $ex){
            return null;
        }
    }

    public function findPopularUsers($currentUser,$limit = 50)
    {
        $qb= $this->createQueryBuilder('u');
        $qb->select('u, RAND() as HIDDEN r');
        $qb->addSelect('(CASE WHEN u IN (SELECT IDENTITY(ml.toUser) FROM CogimixCommonBundle:Listener ml WHERE ml.fromUser = :user) THEN 1 ELSE 0 END) as added');
        $qb->leftJoin('u.listeners','l',Join::WITH,'l.fromUser = :user AND l.accepted=true')
        ->andWhere('u.playlistCount > 0')
        ->setParameter('user', $currentUser->getId())
        ->setMaxResults($limit)
        ->orderBy('r')
        ->setMaxResults($limit);
        $query = $qb->getQuery();
        $query->useQueryCache(true)->useResultCache(true,3600);
        return $query->getResult();

    }

    public function findByUsernameLike($currentUser,$username,$limit=50){
       $qb= $this->createQueryBuilder('u');
       $qb->select('u');
       $qb->addSelect('(CASE WHEN u IN (SELECT IDENTITY(ml.toUser) FROM CogimixCommonBundle:Listener ml WHERE ml.fromUser = :user) THEN 1 ELSE 0 END) as added');
       $qb->where($qb->expr()->like('u.username',$qb->expr()->literal('%'.$username.'%')));
       $qb->andWhere('u.id != :user');
       $qb->leftJoin('u.listeners','l',Join::WITH,'l.fromUser = :user  AND l.accepted=1');
       $qb->setParameter('user', $currentUser->getId());
       $qb->setMaxResults($limit);
       $query=$qb->getQuery();
       $query->useQueryCache(true);
       return $query->getResult();
    }

    public function getListeners($user,$currentUser){
        $qb= $this->createQueryBuilder('u');
        $qb->select('u')
        ->addSelect('(CASE WHEN u IN (SELECT IDENTITY(ml.toUser) FROM CogimixCommonBundle:Listener ml WHERE ml.fromUser = :currentUser) THEN 1 ELSE 0 END) as added')
        ->join('u.myListenings','l',Join::WITH,'l.toUser = :user')
        ->leftJoin('u.myListenings','ll',Join::WITH,'ll.toUser = :currentUser AND ll.accepted=1')
        ->setParameter('user', $user->getId())
        ->setParameter('currentUser', $currentUser->getId());
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult();
    }
    
    public function getListenings($user,$currentUser){
        $qb= $this->createQueryBuilder('u');
        $qb->select('u')
        ->addSelect('(CASE WHEN u IN (SELECT IDENTITY(ml.toUser) FROM CogimixCommonBundle:Listener ml WHERE ml.fromUser = :currentUser) THEN 1 ELSE 0 END) as added')
        ->join('u.listeners','l',Join::WITH,'l.fromUser = :user')
        ->leftJoin('u.myListenings','ll',Join::WITH,'ll.toUser = :currentUser AND ll.accepted = true')
        ->setParameter('user', $user->getId())
        ->setParameter('currentUser', $currentUser->getId());
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult();
    }


    public function getListeningUsersQB($currentUser,$orderBy='shareCount')
    {
        if(!empty($orderBy) && !in_array($orderBy,['shareCount'])){
            $orderBy = 'shareCount';
        }
        $qb= $this->createQueryBuilder('u');
        $qb->join('u.listeners','l',Join::WITH,'l.fromUser = :currentUser');

        $qb->setParameter('currentUser', $currentUser);
        $qb->andWhere('l.accepted = true');
        if(!empty($orderBy)){
            $qb->leftJoin('l.suggestions', 's')
                ->addSelect('COUNT(s.id) AS HIDDEN shareCount')
                ->orderBy('shareCount','DESC')
                ->groupBy('u.id');
        }
        return $qb;
    }

    public function getListeningUsers($currentUser,$orderBy='shareCount'){
        $qb = $this->getListeningUsersQB($currentUser,$orderBy);
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


    public function getPlaylistsFans($playlist)
    {
        $qb= $this->createQueryBuilder('u')
            ->join('u.favoritePlaylists', 'favoritePlaylists',Join::WITH,'favoritePlaylists.playlist  = :playlistId')
            ->setParameter('playlistId', $playlist->getId());

        $query=$qb->getQuery();
        return $query->getResult();
    }

}