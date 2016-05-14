<?php
namespace Cogipix\CogimixCommonBundle\Manager;

use Cogipix\CogimixBundle\Util\StringUtils;
use Cogipix\CogimixCommonBundle\Entity\CacheResults;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\NoResultException;

use Cogipix\CogimixCommonBundle\Manager\AbstractManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\VarDumper\VarDumper;


class CacheManager extends AbstractManager{


    private $serializer;

    private $cachePeriod = "PT30M";

    public function __construct($serializer){
        $this->serializer=$serializer;
    }

    public function getFreshTagsForQuery($query,$serviceTags)
    {
        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addScalarResult('tag','tag');
        $nativeQuery = $this->em->createNativeQuery("
            SELECT DISTINCT(c.tag) as tag, ts_rank_cd(c.tssong,textquery,32 /* rank/(rank+1) */) AS rank
            FROM cache_results c, to_tsquery('simple',?) as textquery
            WHERE textquery @@ c.tssong AND c.tag IN (?) AND c.expireDate > NOW()
            ORDER BY rank DESC
        ",$rsm);
        $nativeQuery->setParameter(1,StringUtils::pgTsQueryAnd($query),\PDO::PARAM_STR);
        $nativeQuery->setParameter(2,$serviceTags,Connection::PARAM_STR_ARRAY);
        $tags = $nativeQuery->getResult();
        if(!empty($tags)){
            return array_map(function($item){
                return $item['tag'];
            },$tags);
        }
        return [];

        $keywords = StringUtils::fullTextMatchAll($query);
        $qb= $this->em->createQueryBuilder();
        $qb->select('DISTINCT(c.tag)')
            ->addSelect("MATCH_AGAINST(c.query, :keywords 'IN BOOLEAN MODE') as HIDDEN score")
            ->addSelect('length(:query)/length(c.query) as HIDDEN lengthRatio')
            ->from('CogimixCommonBundle:CacheResults','c')
            ->where("MATCH_AGAINST(c.query, :keywords 'IN BOOLEAN MODE') > 0 AND c.tag IN (:tags) AND c.expireDate > :now")

            ->orderBy('score','DESC')->orderBy('lengthRatio','ASC')
            ->setParameter('query',$query,\PDO::PARAM_STR)
            ->setParameter('keywords',$keywords,\PDO::PARAM_STR)
            ->setParameter('tags',$serviceTags)
            ->setParameter('now',new \DateTime());
            //->setParameters(array('query'=>$query, 'keywords'=>$keywords,'tags'=>$serviceTags,'now'=>new \DateTime()));
        $query = $qb->getQuery();
        return array_column($query->getScalarResult(),1);
    }

    function fulltext_match_all($query)
    {
        $final = array();
        foreach (array_filter(preg_split('/[\s\'-]+/', $query)) as $word) {
            $final[] = "+$word";
        }
        return implode(' ', $final);
    }

    /**
     *
     * @param string $query
     * @param string $tag
     * @return CacheResults | null
     */
    public function getCacheResults($query,$tag){
        if(!empty($query) && !empty($tag)){
            $qb= $this->em->createQueryBuilder();
            $qb->select('c')
                ->from('CogimixCommonBundle:CacheResults','c')
                ->where('c.query = :query AND c.tag = :tag AND c.expireDate > :now')
                ->setParameters(array('query'=>$query,'tag'=>$tag,'now'=>new \DateTime()))
                ->setMaxResults(1);
            $query = $qb->getQuery();
            $query->useQueryCache(true);
            try{
               $cacheResult= $query->getSingleResult();
              return $this->serializer->deserialize($cacheResult->getResults(),
                       'ArrayCollection<Cogipix\CogimixCommonBundle\Entity\Song>',
                       'json');

            }catch(NoResultException $ex){
                return null;
            }
            catch(\Exception $ex){
                $this->logger->error($ex);
            }
        }
        return null;
    }

    public function insertSimpleCacheResult($query,$tag, $results,$cachePeriodMinute=600)
    {

    }

    protected function createOrUpdateCacheResult($query,$tag, $results,$cachePeriodMinute=600)
    {
        $expireDate= new \DateTime();
        $expireDate->add(new \DateInterval(sprintf("PT%dM",$cachePeriodMinute)));
        try{
            $qb= $this->em->createQueryBuilder();
            $queryDb=$qb->select('c')
            ->from('CogimixCommonBundle:CacheResults','c')
            ->where('c.query = :query AND c.tag = :tag')
            ->setParameters(array('query'=>$query,'tag'=>$tag))
            ->setMaxResults(1)->getQuery();
            $queryDb->useQueryCache(true);
            $cacheResult= $queryDb->getSingleResult();

        }catch(NoResultException $ex){
            $cacheResult = new CacheResults($query, $tag, $expireDate);
        }

        try{
            $cacheResult->setExpireDate($expireDate);
            $cacheResult->setResults($this->serializer->serialize($results,'json'));
            $this->em->persist($cacheResult);
            $this->em->flush();
        }catch (\Exception $ex){
            $this->logger->error($ex);
        }
    }

    public function insertCacheResult($query,$tag, $results,$cachePeriodMinute=600){
        if(is_int($cachePeriodMinute) && $cachePeriodMinute!=0){
            $expireDate= new \DateTime();
            $expireDate->add(new \DateInterval(sprintf("PT%dM",$cachePeriodMinute)));
            try{
                $qb= $this->em->createQueryBuilder();
                $queryDb=$qb->select('c')
                ->from('CogimixCommonBundle:CacheResults','c')
                ->where('c.query = :query AND c.tag = :tag')
                ->setParameters(array('query'=>$query,'tag'=>$tag))
                ->setMaxResults(1)->getQuery();
                $queryDb->useQueryCache(true);
                $cacheResult= $queryDb->getSingleResult();

            }catch(NoResultException $ex){
                 $cacheResult = new CacheResults($query, $tag, $expireDate);
            }

            try{
                $cacheResult->setExpireDate($expireDate);
                $cacheResult->setResults($this->serializer->serialize($results,'json'));
                $this->em->persist($cacheResult);
                $this->em->flush();
                $this->em->detach($cacheResult);
            }catch (\Exception $ex){
                $this->logger->error($ex);
            }
        }

    }

    public function removeExpiredCacheResults(){
        $qb= $this->em->createQueryBuilder();
        $expiredCacheResults=$qb->select('c')
        ->from('CogimixCommonBundle:CacheResults','c')
        ->where('c.query = :query AND c.tag = :tag AND c.expireDate <= :now')
        ->setParameters(array('query'=>$query,'tag'=>$tag,'now'=>new \DateTime()))
        ->getQuery()->getResult();
        if(!empty($expiredCacheResults)){
            foreach($expiredCacheResults as $expiredCacheResult){
                $this->em->remove($expiredCacheResult);
            }
            $this->em->flush();
        }
    }

}