<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


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

    public function getSuggestedTracksToUser($toUser,$onlyUnread){
        $qb= $this->createQueryBuilder('t');
        $qb->select('t','u.username','u.id','s.id sid','s.readed readed');
        $qb->join('t.suggestions','s');
        $qb->join('s.listener','l');
        $qb->join('l.fromUser','u');
        $qb->andWhere('l.toUser = :toUser');
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