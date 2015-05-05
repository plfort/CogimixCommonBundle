<?php
namespace Cogipix\CogimixCommonBundle\Tests;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DoctrineTestCase
 *
 * This is the base class to load doctrine fixtures using the symfony configuration
 */
class DoctrineTestCase extends WebTestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $environment = 'test';

    /**
     * @var bool
     */
    protected $debug = true;

    /**
     * @var string
     */
    protected $entityManagerServiceId = 'doctrine.orm.entity_manager';

    protected $_application;

    /**
     * Constructor
     *
     * @param string|null $name     Test name
     * @param array       $data     Test data
     * @param string      $dataName Data name
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if (!static::$kernel) {
            static::$kernel = self::createKernel(array(
                'environment' => $this->environment,
                'debug'       => $this->debug
            ));
            static::$kernel->boot();
        }

        $this->container = static::$kernel->getContainer();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application(static::$kernel);
        $this->_application->setAutoExit(false);
        $this->em = $this->getEntityManager();
    }

    /**
     * Executes fixtures
     *
     * @param \Doctrine\Common\DataFixtures\Loader $loader
     */
    protected function executeFixtures(Loader $loader)
    {
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * Load and execute fixtures from a directory
     *
     * @param string $directory
     */
    protected function loadFixturesFromDirectory($directory)
    {
        $loader = new Loader();
        $loader->loadFromDirectory($directory);
        $this->executeFixtures($loader);
    }

    protected function loadFixturesFromFile($file)
    {
        $loader = new Loader();
        $loader->loadFromFile($file);
        $this->executeFixtures($loader);
    }

    /**
     * Returns the doctrine orm entity manager
     *
     * @return object
     */
    protected function getEntityManager()
    {
        return $this->container->get($this->entityManagerServiceId);
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }
}