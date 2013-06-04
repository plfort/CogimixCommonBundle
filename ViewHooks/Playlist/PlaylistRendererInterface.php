<?php
namespace Cogipix\CogimixCommonBundle\ViewHooks\Playlist;

interface PlaylistRendererInterface{

    public function getTag();

    public function getListTemplate();

    public function getPlaylists();
}