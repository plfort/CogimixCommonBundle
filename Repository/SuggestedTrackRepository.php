<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;


/**
 *
 * @author plfort - Cogipix
 *
 */
class SuggestedTrackRepository extends EntityRepository{

    public function getUnreadSuggestedTracksCountToUser($toUser){
        $qb= $this->createQueryBuilder('t');
        $qb->select('COUNT(t)');
        $qb->join('t.suggestions','s');
        $qb->join('s.listener','l');
        $qb->join('l.fromUser','u');
        $qb->andWhere('l.toUser = :toUser');
        $qb->setParameter('toUser', $toUser->getId());

        $qb->andWhere('s.readed = 0');

        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getSingleScalarResult();
    }

    
    public function getSuggestedTracksSent($currentUser){
        $qb= $this->createQueryBuilder('t');
        $qb->select('t','tu.username','tu.id','s.id sid');
        $qb->join('t.suggestions','s');
        $qb->join('s.listener','l');
        $qb->join('l.fromUser','u',Join::WITH,'u.id = :userId');
        $qb->join('l.toUser','tu');
        $qb->setParameter('userId', $currentUser->getId());
        $qb->orderBy('s.createDate','DESC');
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult(Query::HYDRATE_ARRAY);
    }
    
    
    public function getSuggestedTracksToUser($toUser,$onlyUnread){
        $qb= $this->_em->createQueryBuilder()

        ->select('NEW Cogipix\CogimixCommonBundle\Entity\SongFromUser(song.id,song.artist,song.title,song.tag,song.entryId,song.thumbnails,song.icon,song.pluginProperties,song.shareable, song.duration,u.username,u.id,s.id,s.readed)')
        ->from('CogimixCommonBundle:SuggestedTrack','t')
        ->join('t.suggestions','s')
        ->join('CogimixCommonBundle:Song','song',Join::WITH,'t.song = song')
        ->join('s.listener','l')
        ->join('l.fromUser','u')
        ->andWhere('l.toUser = :toUser');
        $qb->setParameter('toUser', $toUser->getId());
        $qb->orderBy('s.createDate','DESC');
        if($onlyUnread == true){
            $qb->andWhere('s.readed = 0');
        }
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult();
    }


}