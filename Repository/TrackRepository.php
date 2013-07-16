<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


/**
 *
 * @author plfort - Cogipix
 *
 */
class TrackRepository extends EntityRepository{


    public function getPlaylistShareableTracks($playlistId){
       $qb= $this->createQueryBuilder('t');
       $qb->select('t');
       $qb->join('t.playlist','p',Join::WITH,'p.id = :playlistId' );
       $qb->andWhere('t.shareable = 1');
       $qb->setParameter('playlistId', $playlistId);
       $query=$qb->getQuery();
       $query->useQueryCache(true);
       return $query->getResult();
    }



}