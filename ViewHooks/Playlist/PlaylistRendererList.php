<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Playlist;


/**
 *
 * @author plfort
 *
 */
class PlaylistRendererList implements PlaylistRendererListInterface {


    private $playlistRenderers=array();

    public function addPlaylistRenderer($service)
    {
        if($service instanceof PlaylistRendererListInterface){
            foreach($service->getPlaylistRenderers() as $playlistRenderer){
                $this->addPlaylistRenderer($playlistRenderer);
            }
        }else{
            if($service instanceof PlaylistRendererInterface){
                $this->playlistRenderers[] = $service;
            }
        }
    }

    public function getPlaylistRenderers(){
        return $this->playlistRenderers;
    }


}