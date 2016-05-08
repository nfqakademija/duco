<?php

namespace AppBundle\Providers;

/**
 * Interface ProviderInterface
 * @package AppBundle\Providers
 */
interface ProviderInterface
{
    public function getEvent();

    public function setEvent($event);

    public function getEntityManager();

    public function setEntityManager($entityManager);

    public function getServiceContainer();

    public function setServiceContainer($serviceContainer);

    public function import();
}
