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


    public function getSuggestedTracksToUser($toUser){
        $qb= $this->createQueryBuilder('t');
        $qb->select('t','u.username','u.id','s.id sid');
        $qb->join('t.suggestions','s');
        $qb->join('s.listener','l');
        $qb->join('l.fromUser','u');
        $qb->andWhere('l.toUser = :toUser');
        $qb->setParameter('toUser', $toUser->getId());
        $qb->orderBy('s.createDate','DESC');
        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult();
    }


}