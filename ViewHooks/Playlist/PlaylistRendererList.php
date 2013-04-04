<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Playlist;



class PlaylistRendererList {


    private $playlistRenderers=array();

    public function addPlaylistRenderer($service)
    {
        if($service instanceof PlaylistRendererInterface){
            $this->playlistRenderers[] = $service;
        }
    }

    public function getPlaylistRenderers(){
        return $this->playlistRenderers;
    }


}