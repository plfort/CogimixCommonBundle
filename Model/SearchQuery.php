<?php
namespace Cogipix\CogimixCommonBundle\Model;

use JMS\Serializer\Annotation as JMSSerializer;
class SearchQuery
{

    protected $artistQuery='';

    protected $songQuery;

    /**
     * @JMSSerializer\Exclude()
     * @var unknown_type
     */
    protected $services;

    public function __construct($songQuery = null, $artistQuery = '')
    {
        $this->songQuery = $songQuery;
        $this->artistQuery = $artistQuery;
    }

    public function getArtistQuery()
    {
        return $this->artistQuery;
    }

    public function setArtistQuery($artistQuery)
    {
        $this->artistQuery = $artistQuery;
    }

    public function getSongQuery()
    {
        return $this->songQuery;
    }

    public function setSongQuery($songQuery)
    {
        $this->songQuery = $songQuery;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function setServices($services)
    {
        $this->services = $services;
    }

    public function __toString(){

        return trim($this->artistQuery.' '.$this->songQuery);

    }



}
?>
