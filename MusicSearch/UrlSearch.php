<?php
namespace Cogipix\CogimixCommonBundle\MusicSearch;
use Symfony\Component\DomCrawler\Link;

use Symfony\Component\DomCrawler\Crawler;

use Cogipix\CogimixCommonBundle\Model\ParsedUrl;

use Cogipix\CogimixCommonBundle\Utils\LoggerAwareInterface;

use Cogipix\CogimixCommonBundle\MusicSearch\UrlSearcherInterface;

class UrlSearch implements LoggerAwareInterface
{

    private $urlSearchers;
    private $logger;

    public function searchByUrl($url, $crawlFallback = true)
    {

        try {

            $parsedUrl = new ParsedUrl($url);
            $result = null;
            foreach ($this->urlSearchers as $urlSearcher) {
                if (($result = $urlSearcher->searchByUrl($parsedUrl)) !== null) {
                    return $result;
                }
            }
            if ($result == null && $crawlFallback === true) {

                $result = array();
                $html = $this->getSiteContent($url);
                $crawler = new Crawler($html, $url);
                $iframeSrc = $crawler->filterXpath('//iframe')
                        ->extract(array('src'));
                $embedSrc = $crawler->filterXpath('//embed')
                        ->extract(array('src'));
                $aHref = $crawler->filterXpath('//a')->extract(array('href'));
                $links = array_merge($iframeSrc, $aHref, $embedSrc);
                //var_dump($links);die();
                //$links=$crawler->filter("body a")->links();

                foreach ($links as $url) {

                    $subresult = $this->searchByUrl($url, false);
                    if (null != $subresult) {
                        if (!is_array($subresult)) {
                            $subresult = array($subresult);
                        }

                        $result = array_merge($subresult, $result);
                    }
                }
                //die();
                return $result;
            }
        } catch (\Exception $ex) {
            $this->logger->err($ex->getMessage());
        }

        return array();
    }

    private function getSiteContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function addUrlSearcher(UrlSearcherInterface $urlSearcher)
    {
        $this->urlSearchers[] = $urlSearcher;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
