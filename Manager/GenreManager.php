<?php
namespace Cogipix\CogimixCommonBundle\Manager;

use Cogipix\CogimixCommonBundle\Manager\AbstractManager;

class GenreManager extends AbstractManager
{


    public function getOrAddGenre($genreString)
    {
        $genre = $this->em->getRepository('CogimixCommonBundle:Genre')->findOneByName($genreString);
        if($genre){
            return $genre;
        }

        $genres = $this->em->getRepository('CogimixCommonBundle:Genre')->findAll();


    }
}