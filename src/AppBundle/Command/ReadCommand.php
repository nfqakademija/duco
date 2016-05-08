<?php

namespace AppBundle\Command;

use AppBundle\Providers;
use AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReadCommand
 * @package AppBundle\Command
 */
class ReadCommand extends ContainerAwareCommand
{
    protected $entityManager;
    protected $container;

    /**
     * ReadCommand constructor.
     *
     * @param null|string $name
     * @param $entityManager
     * @param $container
     */
    public function __construct($name, $entityManager, $container)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Read results')
            ->addOption('import-events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('import-events')) {
            $this->importEvents();
        } else {
            $events = $this->getNotImportedEvents();
            foreach ($events as $event) {
                $this->executeProvider($event);
                $this->setDataImported($event->getId());
                $output->writeln('Failas ' . $event->getSource() . ' nuskaitytas');
            }
        }
    }

    /**
     * Import events to database from sql file
     */
    protected function importEvents()
    {
        $fileName = $this->container->get('kernel')->getRootDir() . '/Resources/files/events/events.sql';
        $sql = file_get_contents($fileName);
        $conn = $this->entityManager->getConnection()->prepare($sql);
        $conn->execute();
    }

    /**
     * Returns array where data hasn't imported to database yet
     *
     * @return array
     */
    protected function getNotImportedEvents()
    {
        $q = 'SELECT e FROM AppBundle\Entity\Event e WHERE e.dataImported = 0';
        $query = $this->entityManager->createQuery($q);
        return $query->getResult();
    }

    /**
     * Sets value 1 to data imported column when data import is finished
     *
     * @param int $id
     */
    protected function setDataImported($id)
    {
        $q = 'UPDATE AppBundle\Entity\Event e SET e.dataImported = 1 WHERE e.id = ' . $id;
        $query = $this->entityManager->createQuery($q);
        $query->execute();
    }

    /**
     * Executes provider to import data
     *
     * @param $event
     */
    protected function executeProvider($event)
    {
        $providerName = '\\AppBundle\\Providers\\' . $event->getProviderName();
        $provider = new $providerName();
        $provider->setEvent($event);
        $provider->setEntityManager($this->entityManager);
        $provider->setServiceContainer($this->container);
        $provider->import();
    }
}
