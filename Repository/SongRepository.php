<?php
namespace Cogipix\CogimixCommonBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Cogipix\CogimixCommonBundle\Model\PlaylistConstant;
use Cogipix\CogimixCommonBundle\Model\SearchQuery;

class SongRepository extends EntityRepository{


    public function findSongFullText(SearchQuery $query,$tag)
    {
        $keywordList =  explode(' ',$query->getSongQuery());
        $keywordList = array_filter($keywordList,function($item){
          return mb_strlen($item)>2;
        });

        //$keywords = '+'.join(" +",$keywordList);
        $keywords = join(" ",$keywordList);

        $songs =  $this->createQueryBuilder('s')
        ->addSelect("MATCH_AGAINST(s.title,s.artist, :keywords 'IN BOOLEAN MODE') as HIDDEN score")
            ->addSelect('length(:query)/length(CONCAT(s.title,s.artist)) as HIDDEN lengthRatio')
            ->andWhere('s.tag = :tag')
        ->andWhere("MATCH_AGAINST(s.title,s.artist, :keywords 'IN BOOLEAN MODE') > 0")
        ->setParameter('query', $query->getSongQuery())
            ->setParameter('keywords',$keywords)
        ->setParameter('tag', $tag)
        ->orderBy('score','DESC')->orderBy('lengthRatio','ASC')
        ->setMaxResults(50)
        ->getQuery()
        ->getResult();


    }

    public function getPlaylistShareableTracks($playlistId)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->join('s.playlistTracks', 'pt', Join::WITH, 'pt.playlist = :playlistId');
        $qb->andWhere('s.shareable > :playlist_not_shared');
        $qb->setParameter('playlistId', $playlistId);
        $qb->setParameter('playlist_not_shared', PlaylistConstant::NOT_SHARED);
        $qb->orderBy('pt.order','ASC');
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        return $query->getResult();
    }




    public function getSongsByTagAndEntryIdQB($keys)
    {
        $qb = $this->createQueryBuilder('s')
            ->where("s.tag IN (:tags) AND s.entryId IN (:entryIds)")
            ->setParameter(':tags', $keys['tag'])
            ->setParameter(':entryIds', $keys['entryId']);
        return $qb;
    }


    public function getShareableSongsByTagAndEntryIdPair($array)
    {
        $keys = ['tag'=>[],'entryId'=>[]];
        foreach($array as $song){
            if(!in_array($song['tag'], $keys['tag'])){
                $keys['tag'][] = $song['tag'];
            }

            $keys['entryId'][] = $song['entryId'];
        }

        $qb =$this->getSongsByTagAndEntryIdQB($keys);
        $qb->andWhere('s.shareable = true');
        return $qb->getQuery()->getArrayResult();
    }

    public function getSongsByTagAndEntryId($keys)
    {
        $qb =$this->getSongsByTagAndEntryIdQB($keys);
        return $qb->getQuery()->getResult();
    }


}