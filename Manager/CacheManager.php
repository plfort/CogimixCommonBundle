<?php
namespace Cogipix\CogimixCommonBundle\Manager;

use Cogipix\CogimixCommonBundle\Entity\CacheResults;

use Doctrine\ORM\NoResultException;

use Cogipix\CogimixCommonBundle\Manager\AbstractManager;


class CacheManager extends AbstractManager{


    private $serializer;

    private $cachePeriod = "PT30M";

    public function __construct($serializer){
        $this->serializer=$serializer;
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
                       'ArrayCollection<Cogipix\CogimixCommonBundle\Entity\TrackResult>',
                       'json');

            }catch(NoResultException $ex){
                return null;
            }
            catch(\Exception $ex){
                $this->logger->err($ex->getMessage());
            }
        }
        return null;
    }

    public function insertCacheResult($query,$tag, $results,$cachePeriodMinute=30){
        if(!empty($results) && is_int($cachePeriodMinute) && $cachePeriodMinute!=0){
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
                $cacheResult= $queryDb->getSingleResult();;

            }catch(NoResultException $ex){
                 $cacheResult = new CacheResults($query, $tag, $expireDate);
            }

            try{
                $cacheResult->setExpireDate($expireDate);
                $cacheResult->setResults($this->serializer->serialize($results,'json'));
                $this->em->persist($cacheResult);
                $this->em->flush();
            }catch (\Exception $ex){
                $this->logger->err($ex->getMessage());
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