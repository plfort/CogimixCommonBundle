<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 15/01/16
 * Time: 22:01
 */

namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserLikePlaylistRepository extends EntityRepository
{

    public function findByUser($user, $orders = ['createdAt' => 'DESC'], $limit = 50, $offset = 0,$createdAfter = null)
    {
        $qb = $this->createQueryBuilder('lp')
            ->addSelect('playlist','tags','playlistOwner','userPicture')
            ->join('lp.playlist','playlist')
            ->join('playlist.user','playlistOwner')
            ->join('playlistOwner.picture','userPicture')
            ->leftJoin('playlist.tags','tags')
            ->where('lp.user = :userId')
            ->setMaxResults($limit)
            ->setFirstResult($offset)

            ->setParameter('userId',$user->getId());

        if($createdAfter != null){
            $qb->andWhere('lp.createdAt >= :createdAfter')
                ->setParameter('createdAfter',$createdAfter);
        }

        foreach($orders as $field=>$sortDirection){
            $qb->addOrderBy('lp.'.$field,$sortDirection);
        }
        return $qb->getQuery()->getResult();
    }

}