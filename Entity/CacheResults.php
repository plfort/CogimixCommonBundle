<?php

namespace Cogipix\CogimixCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cache_results",indexes={@ORM\Index(name="search_query_idx", columns={"query", "tag"})})
 * @ORM\Entity
 */
class CacheResults
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $query;

    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $tag;

    /**
     * @ORM\Column(type="datetime")
     * @var unknown_type
     */
    protected $expireDate;

    /**
     * @ORM\Column(type="text")
     * @var unknown_type
     */
    protected $results;

    public function __construct($query,$tag,$expireDate)
    {
        $this->query=$query;
        $this->tag=$tag;
        $this->expireDate=$expireDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getExpireDate()
    {
        return $this->expireDate;
    }

    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;
    }

    public function getResults()
    {
        return gzuncompress(base64_decode($this->results));
        //return $this->results;
    }

    public function setResults($results)
    {
        //$this->results = $results;
        $this->results = base64_encode(gzcompress($results,9));
    }

}
