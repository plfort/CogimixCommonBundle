<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Cogipix\CogimixCommonBundle\Model\PlaylistConstant;

use Cogipix\CogimixCommonBundle\Entity\Playlist;

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
       $qb->andWhere('t.shareable > :playlist_not_shared');
       $qb->setParameter('playlistId', $playlistId);
       $qb->setParameter('playlist_not_shared',PlaylistConstant::$NOT_SHARED);
       $query=$qb->getQuery();
       $query->useQueryCache(true);
       return $query->getResult();
    }



}