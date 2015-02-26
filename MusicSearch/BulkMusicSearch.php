<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;

use Cogipix\CogimixCommonBundle\Model\SearchQuery;
use Cogipix\CogimixCommonBundle\Model\BulkResult;
use Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface;
use Cogipix\CogimixCommonBundle\Comparator\CosineSimilarityComparator;

class BulkMusicSearch implements LoggerAwareInterface
{

    private $musicSearch;

    private $logger;

    private $comparator;

    public function __construct(MusicSearchInterface $musicSearch)
    {
        $this->musicSearch = $musicSearch;
        $this->comparator = new CosineSimilarityComparator();
    }

    public function bulkSearchAndGuess(array $songQueries)
    {
        $results = array();
        $bulkResults = $this->bulkSearch($songQueries,1);
        foreach ($bulkResults as $bulkResult) {
            $levResult = $this->guessBestSong($bulkResult->getSearchQuery(), $bulkResult->getSearchResults());
            if ($levResult['element'] != null) {
                $results[] = $levResult['element'];
            }
        }
        return $results;
    }

    public function bulkSearch(array $songQueries, $sleepTimeSeconds = 0)
    {
        $results = array();
        foreach ($songQueries as $songQuery) {
            try {
                $searchResults = $this->musicSearch->searchMusic($songQuery);
                $bulkresult = new BulkResult($songQuery, $searchResults);
                $results[] = $bulkresult;
                if ($sleepTimeSeconds > 0) {
                    sleep($sleepTimeSeconds);
                }
            } catch (\Exception $ex) {
                $this->logger->err($ex->getMessage());
            }
        }
        return $results;
    }

    private function guessBestSong(SearchQuery $songQuery, $results)
    {
        $shortest = - 1;
        $closest = null;
        $songString = $songQuery->__toString();
        // loop through words to find the closest
        foreach ($results as $track) {

            // calculate the distance between the input word,
            // and the current word
            $distance = $this->comparator->distance($songString, $track->getArtistAndTitle(), false);

            // check for an exact match
            if ($distance == 0) {

                // closest word is this one (exact match)
                $closest = $track;
                $shortest = 0;
                return array(
                    'distance' => $shortest,
                    'element' => $closest
                );
            }

            // if this distance is less than the next found shortest
            // distance, OR if a next shortest word has not yet been found
            if ($distance <= $shortest || $shortest < 0) {
                // set the closest match, and shortest distance
                $closest = $track;
                $shortest = $distance;
            }
        }
        // echo "distance == $shortest , found :".$closest->getArtistAndTitle()." \n\r";
        return array(
            'distance' => $shortest,
            'element' => $closest
        );
    }

    /*
     * (non-PHPdoc)
     * @see \Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface::setLogger()
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}