<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


/**
 *
 * @author plfort - Cogipix
 *
 */
class PlaylistRepository extends EntityRepository{


    public function searchByName($currentUser,$name){
       $qb= $this->createQueryBuilder('p');
       $qb->select('p','u');
       $qb->join('p.user','u');
       $qb->where('p.shared = 1 AND p.name like :name AND p.trackCount > 0');
       $qb->andWhere('u.id NOT IN (SELECT u2.id FROM CogimixCommonBundle:User u2 LEFT JOIN u2.myListenings listenings LEFT JOIN u2.listeners listeners WHERE  (listeners.fromUser = :currentUser AND listeners.accepted = 0) OR (listenings.toUser = :currentUser AND listenings.accepted = 0))');
       $qb->setParameter('name', '%'.$name.'%');
       $qb->setParameter('currentUser',$currentUser);
       $qb->orderBy('p.name','ASC');
       $query=$qb->getQuery();
       $query->useQueryCache(true);
       return $query->getResult();
    }



}