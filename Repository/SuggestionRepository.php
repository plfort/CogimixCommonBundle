<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Cogipix\CogimixCommonBundle\Entity\SuggestedItem;
use Cogipix\CogimixCommonBundle\Entity\SuggestedTrack;
use Cogipix\CogimixCommonBundle\Entity\User;
use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\VarDumper\VarDumper;


/**
 *
 * @author plfort - Cogipix
 *
 */
class SuggestionRepository extends EntityRepository
{


    public function getUnreadSuggestedTracksCountToUser($toUser){
        $qb= $this->createQueryBuilder('s');
        $qb->select('COUNT(s)');
        $qb->join('s.listener','l');
        $qb->join('l.fromUser','u');
        $qb->andWhere('l.toUser = :toUser');
        $qb->setParameter('toUser', $toUser->getId());
        $qb->andWhere('s.readed = 0');

        $query=$qb->getQuery();
        $query->useQueryCache(true);
        return $query->getSingleScalarResult();
    }

    public function getReceivedSuggestions($currentUser,$orders=['createDate'=>'DESC'],$limit=50,$offset=0,$createdAfter = null){
        $qb= $this->_em->createQueryBuilder('s')
            ->select('s,si,l,u,tu')
            ->from('CogimixCommonBundle:Suggestion','s')
            ->join('s.suggestedItem','si')
            ->join('s.listener','l')
            ->join('l.fromUser','u')
            ->join('l.toUser','tu',Join::WITH,'tu.id = :userId')
            ->setParameter('userId', $currentUser->getId())
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if($createdAfter != null){
            $qb->andWhere('s.createDate >= :createdAfter')
                ->setParameter('createdAfter',$createdAfter);
        }



        foreach($orders as $field=>$direction){
            $qb->addOrderBy('s.'.$field,$direction);
        }

        $query=$qb->getQuery();
        $suggestions = $query->getResult();
        if(empty($suggestions)){
            return null;
        }
        $songSuggestion = [];
        foreach($suggestions as $suggestion){
            if($suggestion->getSuggestedItem() instanceof SuggestedTrack){
                $songSuggestion[] = $suggestion->getSuggestedItem()->getId();
            }
        }
        $this->_em->getRepository('CogimixCommonBundle:SuggestedTrack')->findById($songSuggestion);
        return $suggestions;
    }

    /**
     * @param User $currentUser
     * @return int|mixed
     */
    public function countUnreadSuggestions(User $currentUser)
    {
        $qb= $this->_em->createQueryBuilder('s')
            ->select('count(s)')
            ->from('CogimixCommonBundle:Suggestion','s')
            ->join('s.suggestedItem','si')
            ->join('s.listener','l')
            ->leftJoin('l.fromUser','u')
            ->leftJoin('l.toUser','tu')
            ->andWhere('(tu.id = :userId AND s.createDate > :lastNotifDate) OR (u.id = :userId AND s.respondedAt > :lastNotifDate) ')
            ->setParameter('userId',$currentUser->getId())
            ->setParameter('lastNotifDate',$currentUser->getLastNotificationDate());
        try{
            return $qb->getQuery()->getSingleScalarResult();
        }catch(\Exception $ex){
            throw $ex;
            return 0;
        }

    }

    public function getSentSuggestionWithResponse($currentUser,$orders=['respondedAt'=>'DESC'],$limit=50,$offset=0,$createdAfter = null){
        $qb= $this->_em->createQueryBuilder('s')
            ->select('s,si,l,u,tu')
            ->from('CogimixCommonBundle:Suggestion','s')
            ->join('s.suggestedItem','si')
            ->join('s.listener','l');
        $qb->join('l.fromUser','u',Join::WITH,'u.id = :userId');
        $qb->join('l.toUser','tu');
        $qb->andWhere('s.responseMessage IS NOT NULL');
        $qb->setParameter('userId', $currentUser->getId())
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if($createdAfter != null){
            $qb->andWhere('s.respondedAt >= :createdAfter')
                ->setParameter('createdAfter',$createdAfter);
        }

        foreach($orders as $field=>$direction){
            $qb->addOrderBy('s.'.$field,$direction);
        }


        $query=$qb->getQuery();
        $suggestions = $query->getResult();
        if(empty($suggestions)){
            return null;
        }
        $songSuggestion = [];
        foreach($suggestions as $suggestion){
            if($suggestion->getSuggestedItem() instanceof SuggestedTrack){
                $songSuggestion[] = $suggestion->getSuggestedItem()->getId();
            }
        }
        $this->_em->getRepository('CogimixCommonBundle:SuggestedTrack')->findById($songSuggestion);
        return $suggestions;

    }



    public function getSentSuggestion($currentUser,$orders=['createDate'=>'DESC'],$limit=50,$offset=0,$createdAfter = null){
        $qb= $this->_em->createQueryBuilder('s')
            ->select('s,si,l,u,tu')
            ->from('CogimixCommonBundle:Suggestion','s')
            ->join('s.suggestedItem','si')
            ->join('s.listener','l');
        $qb->join('l.fromUser','u');
        $qb->join('l.toUser','tu');
        $qb->andWhere('u.id = :userId AND s.responseMessage IS NULL');
        $qb->setParameter('userId', $currentUser->getId())
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if($createdAfter != null){
            $qb->andWhere('s.createDate >= :createdAfter')
                ->setParameter('createdAfter',$createdAfter);
        }

        foreach($orders as $field=>$direction){
            $qb->addOrderBy('s.'.$field,$direction);
        }


        $query=$qb->getQuery();
        $suggestions = $query->getResult();
        if(empty($suggestions)){
            return null;
        }
        $songSuggestion = [];
        foreach($suggestions as $suggestion){
            if($suggestion->getSuggestedItem() instanceof SuggestedTrack){
                $songSuggestion[] = $suggestion->getSuggestedItem()->getId();
            }
        }
        $this->_em->getRepository('CogimixCommonBundle:SuggestedTrack')->findById($songSuggestion);
        return $suggestions;

    }





    public function getAvailableListenerForSuggestion(User $currentUser, SuggestedItem $suggestedItem = null)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('l', 'listeningUser')
            ->from('CogimixCommonBundle:Listener', 'l')
            ->join('l.toUser', 'listeningUser')
            ->leftJoin('l.suggestions', 'suggestions')
            ->addSelect('COUNT(suggestions.id) AS HIDDEN shareCount');
        if ($suggestedItem) {
            $subQuery = $this->_em->createQueryBuilder()
                ->select('l2.id')
                ->from('CogimixCommonBundle:SuggestedItem', 'suggestedItem')
                ->andWhere('suggestedItem.id = :suggestedItem')
                ->join('suggestedItem.suggestions', 'suggestions2')
                ->join('suggestions2.listener', 'l2')
                ->andWhere('l2.fromUser = :currentUser AND l2.accepted = true');

            $qb->andWhere('l NOT IN ('.$subQuery->getDQL().')');
            $qb->setParameter('suggestedItem', $suggestedItem);

        }

        $qb->andWhere('l.fromUser = :currentUser AND l.accepted = true')
            ->orderBy('shareCount', 'DESC')
            ->groupBy('l.id');

        $qb->setParameter('currentUser', $currentUser);


        return $qb->getQuery()->getResult();
    }


}