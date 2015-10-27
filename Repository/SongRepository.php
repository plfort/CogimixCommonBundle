<?php
namespace Cogipix\CogimixCommonBundle\Repository;

use Cogipix\CogimixBundle\Util\StringUtils;
use Cogipix\CogimixCommonBundle\Entity\Song;
use Cogipix\CogimixCommonBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Cogipix\CogimixCommonBundle\Model\PlaylistConstant;
use Cogipix\CogimixCommonBundle\Model\SearchQuery;

class SongRepository extends EntityRepository{


    public function findSongFullText(SearchQuery $query,$tag)
    {

        $keywords = StringUtils::fullTextMatchAll($query->getSongQuery());

        return  $this->createQueryBuilder('s')
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

    public function getSongFans(Song $song)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('CogimixCommonBundle:User','u')
            ->join('u.favoriteSongs','favoriteSongs',Join::WITH,'favoriteSongs.song = :songId')
            ->setParameter('songId',$song->getId());

        return $qb->getQuery()->getResult();

    }

    public function getUsersFavoriteSongs(User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
            ->from('CogimixCommonBundle:Song','s')
            ->join('s.fans','userLikeSong',Join::WITH,'userLikeSong.user = :userId')
            ->setParameter('userId',$user->getId());

        return $qb->getQuery()->getResult();

    }


    public function getPlaylistShareableTracks($playlistId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p,s')
        ->from('CogimixCommonBundle:PlaylistTrack','p')
        ->join('p.song', 's')
        ->andWhere('p.playlist = :playlistId AND s.shareable > :playlist_not_shared')
        ->setParameter('playlistId', $playlistId)
        ->setParameter('playlist_not_shared', PlaylistConstant::NOT_SHARED)
        ->orderBy('p.order','ASC');
        $query = $qb->getQuery();


        $playlistTracks =  $query->getResult();
        $songs = [];
        if($playlistTracks){
            foreach($playlistTracks as $playlistTrack){
                $songs[]=$playlistTrack->getSong();
            }
        }
        return $songs;
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

    public function getPopulareSongsQB()
    {
        return  $this->createQueryBuilder('s')
            ->addSelect("COUNT(DISTINCT pt.user) AS HIDDEN playCount")
            ->join('s.playedTracks','pt')
            ->andWhere('s.shareable = 1')
            ->groupBy('s.id')

            ->orderBy('playCount','DESC')
            ->having('AVG(pt.playDuration) > 30');

    }

    public function getPopularSongsBetween($minDate,$maxDate = null,$limit=100){
        if(!$maxDate){
            $maxDate = new \DateTime();
        }

        $qb =  $this->getPopulareSongsQB()
            ->andWhere('pt.playDate BETWEEN :minDate AND :maxDate')
            ->setParameter('minDate',$minDate)
            ->setParameter('maxDate',$maxDate)
            ->setMaxResults($limit);

        $query = $qb->getQuery();
        $query->useResultCache(true,3600,'popular_query_simple');
        return $query->getResult();
    }


}