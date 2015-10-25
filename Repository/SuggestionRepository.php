<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Cogipix\CogimixCommonBundle\Entity\SuggestedItem;
use Cogipix\CogimixCommonBundle\Entity\User;
use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


/**
 *
 * @author plfort - Cogipix
 *
 */
class SuggestionRepository extends EntityRepository
{


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
                ->andWhere('l2.fromUser = :currentUser AND l2.accepted = 1');

            $qb->andWhere('l NOT IN ('.$subQuery->getDQL().')');
            $qb->setParameter('suggestedItem', $suggestedItem);

        }

        $qb->andWhere('l.fromUser = :currentUser AND l.accepted = 1')
            ->orderBy('shareCount', 'DESC')
            ->groupBy('l.id');

        $qb->setParameter('currentUser', $currentUser);


        return $qb->getQuery()->getResult();
    }


}