<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 22/10/15
 * Time: 19:40
 */

namespace Cogipix\CogimixCommonBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository {


    /**
     * Searches tags with term.
     *
     * @param string  $query Search query
     * @param boolean $exact Search exact query if true
     * @param boolean $field Return this field instead of whole entity
     * @param integer $limit Limit result set
     *
     * @return array
     */
    public function search($term, $exact = false, $field = null, $limit = null)
    {
        $result = $this->searchQB($term, $exact, $field, $limit)
            ->getQuery()
            ->getResult()
        ;
        if ($field) {
            $rows = $result;
            $result = array();
            foreach ($rows as $row) {
                $result[] = $row[$field];
            }
        }
        return $result;
    }

    /**
     * Returns QueryBuilder for searching tags with term.
     *
     *
     */
    public function searchQB($term, $exact = false, $field = null, $limit = null)
    {
        if(strpos($term,'#') !== 0){
            $term='#'.$term;
        }

        if (!$exact && strpos($term, '%') === false) {
            $term = sprintf('%%%s%%', $term);
        }
        $query = $this->findAllQB()
            ->where('LOWER(tag.label) like :term')
            ->setParameter('term', strtolower($term))
        ;
        if ($field) {
            $query->select(sprintf('tag.%s', $field));
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query;
    }
    /**
     * Returns QueryBuilder for all tags.
     *
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function findAllQB($alias = 'tag')
    {
        return $this->createQueryBuilder($alias)
            ->orderBy($alias . '.label', 'ASC')
            ;
    }
}