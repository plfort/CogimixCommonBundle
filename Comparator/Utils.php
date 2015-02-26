<?php
namespace Cogipix\CogimixCommonBundle\Comparator;

class Utils
{
    static function normalize($string)
    {

        return self::removeAccents(strtolower(trim($string)));
    }

    static function removeAccents($string)
    {
        //Normalisation de la chaine utf8 en mode caractère + accents
        //Suppression des accents
        return preg_replace('~\p{Mn}~u', '', \Normalizer::normalize($string, \Normalizer::FORM_D));
    }
}