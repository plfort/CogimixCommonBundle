<?php
namespace Cogipix\CogimixCommonBundle\Comparator;


interface ComparatorInterface
{

    public function sort(&$array,$switch);

    public function normalize($string);

    public function distance($a,$b,$normalized = true);
}