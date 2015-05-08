<?php
namespace Cogipix\CogimixCommonBundle\Comparator;

use \NlpTools\Similarity\CosineSimilarity;
use \NlpTools\Tokenizers\WhitespaceTokenizer;

class CosineSimilarityComparator implements ComparatorInterface
{

    /**
     *
     * @var CosineSimilarity
     */
    private $cosine;

    private $tokenizer;

    public function __construct()
    {
        $this->cosine = new CosineSimilarity();
        $this->tokenizer = new WhitespaceTokenizer();
    }

    /*
     * (non-PHPdoc)
     * @see \Cogipix\CogimixBundle\Services\Comparator\ComparatorInterface::sort()
     */
    public function sort(&$array, $switch)
    {
        $setOrigin = $this->normalize($switch);
        usort($array, function ($a, $b) use($setOrigin) {

            $simA = $this->distance($setOrigin, $this->normalize($a->getArtist().' '.$a->getTitle()));
            $simB = $this->distance($setOrigin, $this->normalize($b->getArtist().' '.$b->getTitle()));

            if ($simA == $simB) {
                return 0; // equality
            } elseif ($simA > $simB) { // $a is worst than $b
                return 1; // so $a is higher than $b for ascending sort
            } else {
                return -1;
            }
        });
    }

    public function normalize($string)
    {
        return $this->tokenizer->tokenize(Utils::normalize($string));
    }

    /*
     * (non-PHPdoc)
     * @see \Cogipix\CogimixBundle\Services\Comparator\ComparatorInterface::compare()
     */
    public function distance($a, $b,$normalized = true)
    {

        if(!$normalized){
            $a = $this->normalize($a);
            $b = $this->normalize($b);
        }

        $dist =  $this->cosine->dist($a, $b);
        return abs(round($dist,4));
    }
}