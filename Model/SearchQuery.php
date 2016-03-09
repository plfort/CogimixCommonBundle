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

    protected $sort=true;

    protected $useCache = true;

    public function __construct($songQuery = null, $artistQuery = '')
    {
        $this->songQuery = $songQuery;
        $this->artistQuery = $artistQuery;
        $this->services = [];
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

    public function getSort()
    {
        return $this->sort;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isUseCache()
    {
        return $this->useCache;
    }

    /**
     * @param boolean $useCache
     */
    public function setUseCache($useCache)
    {
        $this->useCache = $useCache;
    }




}
?>
