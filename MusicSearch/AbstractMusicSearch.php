<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;
use Cogipix\CogimixCommonBundle\Manager\CacheManager;

use Cogipix\CogimixCommonBundle\Plugin\PluginInterface;

use Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface;

use Cogipix\CogimixCommonBundle\Model\SearchQuery;

/**
 *
 * @author plfort - Cogipix
 *
 */
abstract class AbstractMusicSearch implements PluginInterface,LoggerAwareInterface
{
    /**
     * @var SearchQuery $searchQuery
     */
    protected $searchQuery;
    protected $logger;
    /**
     *
     * @var CacheManager $cacheManager
     */
    protected $cacheManager;

    abstract protected function buildQuery();
    abstract protected function parseResponse($response);
    abstract protected function executeQuery();

    public function searchMusic(SearchQuery $search)
    {
        $this->logger->debug('Search music in '.get_class($this) );
        $this->searchQuery = $search;
        $resultTag = $this->getResultTag();
        if(null !=$this->cacheManager){
            $cacheResults = $this->cacheManager->getCacheResults($search->getSongQuery(),$resultTag );
            if(!empty($cacheResults)){
                $this->logger->debug('Find results in cache for '.$resultTag);
                return $cacheResults;
            }else{
                $this->buildQuery();
                $results= $this->executeQuery();
                $this->cacheManager->insertCacheResult($search->getSongQuery(), $resultTag, $results);
                return $results;
            }
        }else{
            $this->buildQuery();
            return $this->executeQuery();
        }
    }


    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    public function setSearchQuery(SearchQuery $searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    public function setLogger($logger){

        $this->logger=$logger;
    }

    public function setCacheManager($cacheManager){

        $this->cacheManager=$cacheManager;
    }


}
