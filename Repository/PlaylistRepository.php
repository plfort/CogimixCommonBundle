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


    public function searchByName($name){
       $qb= $this->createQueryBuilder('p');
       $qb->select('p','u');
       $qb->join('p.user','u');
       $qb->where('p.shared = 1 AND p.name like :name AND p.trackCount > 0');
       $qb->setParameter('name', '%'.$name.'%');
       $query=$qb->getQuery();
       $query->useQueryCache(true);
       return $query->getResult();
    }



}