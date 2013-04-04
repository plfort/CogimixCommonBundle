<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;
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

    abstract protected function buildQuery();
    abstract protected function parseResponse($response);
    abstract protected function executeQuery();

    public function searchMusic(SearchQuery $search)
    {
        $this->logger->info('Search music in '.get_class($this) );
        $this->searchQuery = $search;
        $this->buildQuery();
        return $this->executeQuery();
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


}
