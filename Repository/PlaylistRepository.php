<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\NoResultException;

use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


/**
 *
 * @author plfort - Cogipix
 *
 */
class PlaylistRepository extends EntityRepository{


    public function isPlaylistAlreadyInFavorite($currentUser, $playlist){
        $q=$this->_em->createQuery("SELECT 1 FROM CogimixCommonBundle:User u WHERE u=:currentUser AND :playlist MEMBER OF u.favoritePlaylists")
        ->setParameter('currentUser',$currentUser)
        ->setParameter('playlist', $playlist);
        try{
            $q->getSingleScalarResult();
            return true;
        }
        catch(NoResultException $ex){
            return false;
        }
    }

    public function getSharedPlaylist($playlistId,$currentUser){

        $qb= $this->createQueryBuilder('p');
        $qb->select('distinct p,u');
        $qb->join('p.user','u');
        $qb->leftJoin('u.listeners','ml');
        $qb->where('p.id = :id AND (u = :currentUser OR p.shared = 1  OR (p.shared = 2 AND ml.fromUser = :currentUser AND ml.accepted = 1))');
        $qb->andWhere('u.id NOT IN (SELECT u2.id FROM CogimixCommonBundle:User u2 LEFT JOIN u2.myListenings listenings LEFT JOIN u2.listeners listeners WHERE  (listeners.fromUser = :currentUser AND listeners.accepted = 0) OR (listenings.toUser = :currentUser AND listenings.accepted = 0))');
        $qb->setParameter('id',$playlistId);
        $qb->setParameter('currentUser',$currentUser);

        $query=$qb->getQuery();
        $query->useQueryCache(true);

        try{
            return $query->getSingleResult();
        }catch(NoResultException $ex){

            return null;
        }
    }

    public function searchByName($currentUser,$name,$limit=30,$listenrId=null){
       $qb= $this->createQueryBuilder('p');
       $qb->select('distinct p,u');

       $qb->join('p.user','u');
       $qb->leftJoin('u.listeners','ml');
       $qb->where('(p.shared = 1  OR (p.shared = 2 AND ml.fromUser = :currentUser AND ml.accepted = 1)) AND (p.name like :name) AND p.trackCount > 0');
       if($currentUser != null){
           $qb->andWhere('u.id NOT IN (SELECT u2.id FROM CogimixCommonBundle:User u2 LEFT JOIN u2.myListenings listenings LEFT JOIN u2.listeners listeners WHERE  (listeners.fromUser = :currentUser AND listeners.accepted = 0) OR (listenings.toUser = :currentUser AND listenings.accepted = 0))');
       }
       if($listenrId != null){
           $qb->andWhere('u.id = :listenerId');
           $qb->setParameter('listenerId',$listenrId);
       }
       $qb->setParameter('name', '%'.$name.'%');
       $qb->setParameter('currentUser',$currentUser);
       if(!empty($limit)){
           $qb->setMaxResults($limit);
       }

       $qb->addOrderBy('p.fanCount','DESC');
       $qb->addOrderBy('p.updateDate','DESC');

       $query=$qb->getQuery();
       $query->useQueryCache(true);
       //$query->useResultCache(true,600);
       return $query->getResult();
    }



}