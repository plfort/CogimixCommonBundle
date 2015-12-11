<?php
namespace Cogipix\CogimixCommonBundle\Repository;


use Cogipix\CogimixCommonBundle\Entity\User;
use Doctrine\ORM\NoResultException;

use Doctrine\ORM\Query\Expr\Join;

use Doctrine\ORM\EntityRepository;


/**
 *
 * @author plfort - Cogipix
 *
 */
class PlaylistRepository extends EntityRepository{


    public function getUserPlaylistWithTags(User $user){
        $qb = $this->_em->createQueryBuilder()
            ->select('p,tags')
            ->from('CogimixCommonBundle:Playlist','p')
            ->leftJoin('p.tags','tags')
            ->where('p.user = :userId')
            ->orderBy('p.name')
            ->setParameter('userId',$user->getId());

        return $qb->getQuery()->getResult();
    }

    public function getPlaylistFolders(User $user)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('pf,p')
            ->from('CogimixCommonBundle:PlaylistFolder','pf')
            ->leftJoin('pf.playlists','p')
            ->where('pf.user = :userId')
            ->setParameter('userId',$user->getId());

        return $qb->getQuery()->getResult();

    }

    public function isPlaylistAlreadyInFavorite($currentUser, $playlist){
        $q=$this->_em->createQuery("SELECT 1 FROM CogimixCommonBundle:User u WHERE u=:currentUser AND :playlist MEMBER OF u.favoritePlaylists")
        ->setParameter('currentUser',$currentUser)
        ->setParameter('playlist', $playlist);
        try{
            $q->getSingleScalarResult();
            return true;
        }
        catch(NoResultException $ex){
            return false;
        }
    }

    public function getSharedPlaylist($playlistId,$currentUser){

        $qb= $this->createQueryBuilder('p');
        $qb->select('distinct p');
        $qb->addSelect('playlistTracks,song')
        ->leftJoin('p.playlistTracks','playlistTracks')
        ->leftJoin('playlistTracks.song','song');
        $qb->join('p.user','u');
        $qb->leftJoin('u.listeners','ml');
        $qb->where('p.id = :id AND (song.id IS NULL OR song.shareable = true) AND (u = :currentUser OR p.shared = 1  OR (p.shared = 2 AND ml.fromUser = :currentUser AND ml.accepted = 1))');
        $qb->andWhere('u.id NOT IN (SELECT u2.id FROM CogimixCommonBundle:User u2 LEFT JOIN u2.myListenings listenings LEFT JOIN u2.listeners listeners WHERE  (listeners.fromUser = :currentUser AND listeners.accepted = 0) OR (listenings.toUser = :currentUser AND listenings.accepted = 0))');

        $qb->setParameter('id',$playlistId);
        $qb->setParameter('currentUser',$currentUser);

        $query=$qb->getQuery();
        $query->useQueryCache(true);

        try{
            return $query->getSingleResult();
        }catch(NoResultException $ex){

            return null;
        }
    }


    public function searchByNameAndTags($currentUser,$name,$tags=[],$limit=30,$listenrId=null){
        $qb= $this->createQueryBuilder('p');
        $qb->select('distinct p,u')
            ->join('p.user','u')

            ->leftJoin('u.listeners','ml')
            ->where('(p.shared = 1  OR (p.shared = 2 AND ml.fromUser = :currentUser AND ml.accepted = 1) OR (p.shared = 0 and p.user = :currentUser  ) )  AND (p.name like :name) AND p.trackCount > 0');
        if($currentUser != null){
            $qb->andWhere('u.id NOT IN (SELECT u2.id FROM CogimixCommonBundle:User u2 LEFT JOIN u2.myListenings listenings LEFT JOIN u2.listeners listeners WHERE  (listeners.fromUser = :currentUser AND listeners.accepted = 0) OR (listenings.toUser = :currentUser AND listenings.accepted = 0))');
        }

        $qb->addSelect('tags');
        $qb->leftJoin('p.tags','tags');

        if(!empty($tags)){
            $qb->leftJoin('p.tags','tagsFilter');
            $qb->andWhere('tagsFilter.label IN (:tags)')
                ->setParameter('tags',$tags);
        }


        if($listenrId != null){
            $qb->andWhere('u.id = :listenerId');
            $qb->setParameter('listenerId',$listenrId);
        }
        $qb->setParameter('name', '%'.$name.'%');
        $qb->setParameter('currentUser',$currentUser);
        if(!empty($limit)){
            $qb->setMaxResults($limit);
        }

        //$qb->addOrderBy('p.fanCount','DESC');
        $qb->addOrderBy('p.createDate','DESC');

        $query=$qb->getQuery();
        $query->useQueryCache(true);
        //$query->useResultCache(true,600);
        return $query->getResult();
    }

    public function searchByName($currentUser,$name,$limit=30,$listenrId=null){
       $qb= $this->createQueryBuilder('p');
       $qb->select('distinct p,u,tags')
        ->join('p.user','u')
       ->leftJoin('p.tags','tags')
       ->leftJoin('u.listeners','ml')
       ->where('(p.shared = 1  OR (p.shared = 2 AND ml.fromUser = :currentUser AND ml.accepted = 1)) AND (p.name like :name) AND p.trackCount > 0');
       if($currentUser != null){
           $qb->andWhere('u.id NOT IN (SELECT u2.id FROM CogimixCommonBundle:User u2 LEFT JOIN u2.myListenings listenings LEFT JOIN u2.listeners listeners WHERE  (listeners.fromUser = :currentUser AND listeners.accepted = 0) OR (listenings.toUser = :currentUser AND listenings.accepted = 0))');
       }
       if($listenrId != null){
           $qb->andWhere('u.id = :listenerId');
           $qb->setParameter('listenerId',$listenrId);
       }
       $qb->setParameter('name', '%'.$name.'%');
       $qb->setParameter('currentUser',$currentUser);
       if(!empty($limit)){
           $qb->setMaxResults($limit);
       }

       //$qb->addOrderBy('p.fanCount','DESC');
       $qb->addOrderBy('p.createDate','DESC');

       $query=$qb->getQuery();
       $query->useQueryCache(true);
       //$query->useResultCache(true,600);
       return $query->getResult();
    }


    public function getUserFavoritePlaylists($user)
    {
         $qb= $this->createQueryBuilder('p')
         ->addSelect('owner')
         ->join('p.fans', 'fan',Join::WITH,'fan.id  = :userId')
         ->join('p.user','owner')
         ->setParameter('userId', $user->getId());

         $query=$qb->getQuery();
         $query->useQueryCache(true);
         //$query->useResultCache(true,600);
         return $query->getResult();
    }


}