<?php
namespace Cogipix\CogimixCommonBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Cogipix\CogimixCommonBundle\Tests\DoctrineTestCase;
use Cogipix\CogimixCommonBundle\Entity\PlayedTrack;
use Cogipix\CogimixCommonBundle\Entity\PlaylistTrack;
use Cogipix\CogimixCommonBundle\Tests\Utils\PlaylistFactory;
use Cogipix\CogimixCommonBundle\Tests\Utils\UserFactory;

class SongRepositoryTest extends DoctrineTestCase
{

    /**
     * Set up repository test
     */
    public function setUp()
    {

        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:create");

    }

    /**
     * No shareable songs
     */
    public function test_getPlaylistShareableTracks_1()
    {
        $this->loadFixturesFromFile(__DIR__ . '/DataFixtures/LoadSongsData_getPlaylistShareableTracks_1.php');
        $repo = $this->getRepository();
        $songs = $repo->getPlaylistShareableTracks(1);
        $this->assertCount(0, $songs, 'Should return 0 songs');

    }

    /**
     * All songs shareable
     */
    public function test_getPlaylistShareableTracks_2()
    {
        $this->loadFixturesFromFile(__DIR__ . '/DataFixtures/LoadSongsData_getPlaylistShareableTracks_2.php');
        $repo = $this->getRepository();
        $songs = $repo->getPlaylistShareableTracks(1);
        $this->assertCount(10, $songs, 'Should return 10 songs');

    }

    /**
     * Returns repository
     *
     * @return \Cogipix\CogimixCommonBundle\Repository\SongRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('\Cogipix\CogimixCommonBundle\Entity\Song');
    }

}