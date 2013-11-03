<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;

use Cogipix\CogimixCommonBundle\Model\SearchQuery;
use Cogipix\CogimixCommonBundle\Model\BulkResult;
class BulkMusicSearch{

	private $musicSearch;


	public function __construct(AbstractMusicSearch $musicSearch){
		$this->musicSearch = $musicSearch;
	}

	public function bulkSearchAndGuess(array $songQueries){
		$results = array();
		$bulkResults = $this->bulkSearch($songQueries);
		foreach($bulkResults as $bulkResult){
			$track = $this->guessBestSong($bulkResult->getSearchQuery(), $bulkResult->getSearchResults());
			if($track != null){
				$results[]=$track;
			}
		}
		return $results;
	}

	public function bulkSearch(array $songQueries){
		$results = array();
		foreach($songQueries as $songQuery){
			$searchResults = $this->musicSearch->searchMusic($songQuery);
			$bulkresult = new BulkResult($songQuery, $searchResults);
			$results[]= $bulkresult;
		}
		return $results;
	}

	private function guessBestSong(SearchQuery $songQuery,$results){

		$shortest = -1;
		$closest = null;
		// loop through words to find the closest
		foreach ($results as $track) {

			// calculate the distance between the input word,
			// and the current word
			$lev = levenshtein($songQuery->_toString(), $track->__toString());

			// check for an exact match
			if ($lev == 0) {

				// closest word is this one (exact match)
				$closest = $track;
				$shortest = 0;

				// break out of the loop; we've found an exact match
				break;
			}

			// if this distance is less than the next found shortest
			// distance, OR if a next shortest word has not yet been found
			if ($lev <= $shortest || $shortest < 0) {
				// set the closest match, and shortest distance
				$closest  = $word;
				$shortest = $lev;
			}
		}

		return $closest;

	}

	private function compare($songQuery,$results){


	}

}