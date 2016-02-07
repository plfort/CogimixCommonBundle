<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 15/01/16
 * Time: 22:01
 */

namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\VarDumper\VarDumper;

class UserLikeSongRepository extends EntityRepository
{

    public function findByUser($user, $orders = ['createdAt' => 'DESC'],  $limit = 50,$offset = 0,$createdAfter = null)
    {
        $qb = $this->createQueryBuilder('ls')
            ->addSelect('song')
            ->join('ls.song','song')
            ->where('ls.user = :userId')
            ->setMaxResults($limit)
            ->setFirstResult($offset)

            ->setParameter('userId',$user->getId());

        if($createdAfter != null){
            $qb->andWhere('ls.createdAt >= :createdAfter')
                ->setParameter('createdAfter',$createdAfter);
        }
        foreach($orders as $field=>$sortDirection){
            $qb->addOrderBy('ls.'.$field,$sortDirection);
        }
        return $qb->getQuery()->getResult();
    }

}