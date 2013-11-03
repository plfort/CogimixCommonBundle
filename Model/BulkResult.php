<?php
namespace Cogipix\CogimixCommonBundle\Model;

class BulkResult
{
	protected $searchQuery;
    protected $searchResults;


    public function __construct($searchQuery, $searchResults)
    {
        $this->searchQuery = $searchQuery;
        $this->searchResults = $searchResults;
    }


	public function getSearchQuery() {
		return $this->searchQuery;
	}
	public function setSearchQuery($searchQuery) {
		$this->searchQuery = $searchQuery;
		return $this;
	}
	public function getSearchResults() {
		return $this->searchResults;
	}
	public function setSearchResults($searchResults) {
		$this->searchResults = $searchResults;
		return $this;
	}






}
?>