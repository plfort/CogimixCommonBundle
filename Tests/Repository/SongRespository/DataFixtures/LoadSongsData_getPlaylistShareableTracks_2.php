<?php
namespace Cogipix\CogimixCommonBundle\Tests\Repository\SongRespository\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cogipix\CogimixCommonBundle\Entity\Song;
use Cogipix\CogimixCommonBundle\Tests\Utils\SongFactory;
use Cogipix\CogimixCommonBundle\Tests\Utils\UserFactory;
use Cogipix\CogimixCommonBundle\Tests\Utils\PlaylistFactory;
use Cogipix\CogimixCommonBundle\Entity\PlaylistTrack;

/**
 * Loads songs data
 */
class LoadSongsData_getPlaylistShareableTracks_2 extends AbstractFixture
{

    /**
     * Load fixtures
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();
        gc_collect_cycles(); // Could be useful if you have a lot of fixtures
        $this->generateFakeSongs($manager);
        $user = UserFactory::generateUser();
        $manager->persist($user);
        $playlist = PlaylistFactory::generatePlaylist($user);

        $manager->persist($playlist);
        $this->generatedPlaylistTracks($manager,$playlist,$user,10);

        $manager->flush();
    }

    protected function generatedPlaylistTracks($manager,$playlist,$user,$count = 30)
    {
        for($i=0;$i<$count;$i++) {
            $p1 = new PlaylistTrack();
            $p1->setPlaylist($playlist);
            $p1->setSong($this->getReference( sprintf('song_%d', $i)));
            $p1->setOrder($i);
            $p1->setCreated(new \DateTime);
            $p1->setUpdated(new \DateTime);
            $p1->setAddedBy($user);
            $manager->persist($p1);
        }
    }

    protected function generateFakeSongs($manager,$count = 50)
    {
        for($i=0;$i<$count;$i++) {
            $song = SongFactory::generateFakeSong();
            $song->setShareable(true);
            $manager->persist($song);
            $this->addReference( sprintf('song_%d', $i), $song);
        }
        $manager->flush();
    }

}