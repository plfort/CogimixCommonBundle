<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;

use Cogipix\CogimixCommonBundle\Model\ParsedUrl;

interface UrlSearcherInterface{

    public function canParse($host);

    public function searchByUrl(ParsedUrl $url);

}