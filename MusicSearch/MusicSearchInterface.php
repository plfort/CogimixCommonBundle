<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;
use Cogipix\CogimixCommonBundle\Model\SearchQuery;
/**
 *
 * @author plfort - Cogipix
 *
 */
interface MusicSearchInterface{
     function searchMusic(SearchQuery $search);
}

?>