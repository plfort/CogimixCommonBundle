<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;
use Cogipix\CogimixCommonBundle\Manager\CacheManager;

use Cogipix\CogimixCommonBundle\Plugin\PluginInterface;

use Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface;

use Cogipix\CogimixCommonBundle\Model\SearchQuery;
use Cogipix\CogimixBundle\Manager\SongManager;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 *
 * @author plfort - Cogipix
 *
 */
abstract class AbstractMusicSearch implements PluginInterface,
        LoggerAwareInterface
{
    private $popularKeyword = '###popularSongs###';
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

    /**
     *
     * @var SongManager $songManager
     */
    protected $songManager;

    abstract protected function buildQuery();
    abstract protected function parseResponse($response);
    abstract protected function executeQuery();

    protected function executePopularQuery(){
        return array();
    }




    public function getPopularSongs(SearchQuery $searchQuery){
        $this->logger->debug('Get popular music in ' . get_class($this));
        if (null != $this->cacheManager) {
            $resultTag = $this->getResultTag();
            $cacheResults = $this->cacheManager
                    ->getCacheResults($this->popularKeyword, $resultTag);
            if (!empty($cacheResults)) {
                $this->logger->debug('Find results in cache for ' . $resultTag);
                return $cacheResults;
            } else {

                $results = $this->executePopularQuery();
                if($results){
                    $results = $this->songManager->insertAndGetSongs($results);
                    $this->cacheManager
                    ->insertCacheResult($this->popularKeyword,
                        $resultTag, []);
                }

                /*$this->cacheManager
                        ->insertCacheResult($this->popularKeyword,
                                $resultTag, $results);*/
                return $results;
            }
        } else {

            return $this->executePopularQuery();
        }
    }

    public function searchMusic(SearchQuery $search)
    {

        $this->searchQuery = $search;
        $resultTag = $this->getResultTag();
        if (null == $this->cacheManager) {
            $cacheResults = $this->cacheManager
                    ->getCacheResults($search->getSongQuery(), $resultTag);
            if (!empty($cacheResults)) {
                $this->logger->debug('Find results in cache for ' . $resultTag);
                return $cacheResults;
            } else {
                $this->buildQuery();
                $results = $this->executeQuery();

                if(!empty($results)){
                    $results = $this->songManager->insertAndGetSongs($results);
                	$this->cacheManager
                	->insertCacheResult($search->getSongQuery(),
                			$resultTag, []);
                }

                return $results;
            }
        } else {
            $this->buildQuery();
            $results = $this->executeQuery();
            return $this->songManager->insertAndGetSongs($results);
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

    public function setLogger($logger)
    {

        $this->logger = $logger;
    }

    public function setCacheManager($cacheManager)
    {

        $this->cacheManager = $cacheManager;
    }

    public function setSongManager(SongManager $songManager)
    {
        $this->songManager = $songManager;
    }

}
