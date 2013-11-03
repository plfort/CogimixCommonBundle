<?php
namespace Cogipix\CogimixCommonBundle\Model;

class ParsedUrl{

    public $url;
    public $host = null;
    public $path = null;
    public $query = null;
    public $fragment = null;

    public function __construct($url){
        $this->url=$url;
        if( strpos($url, 'http://') === 0 ||strpos($url, 'https://')===0 ){
            $parsedUrl=parse_url($url);
        }else{
            $parsedUrl=parse_url('http://'.$url);
        }


        if(isset($parsedUrl['host'])){
            $this->host=$parsedUrl['host'];
        }

        if(isset($parsedUrl['path'])){
            $trimedPath = trim($parsedUrl['path'], "/");
            if(!empty($trimedPath)){
                  $this->path=explode('/', $trimedPath);
            }
        }

        if(isset($parsedUrl['fragment'])){
           $this->fragment= $parsedUrl['fragment'];
        }

        if(isset($parsedUrl['query'])){
                parse_str($parsedUrl['query'],$this->query);
        }
    }

}
