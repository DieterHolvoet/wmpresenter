<?php

namespace Drupal\wmpresenter;

use Drupal\wmpresenter\Entity\HasPresenterInterface;
use Drupal\wmpresenter\Entity\PresenterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PresenterFactory implements PresenterFactoryInterface
{
    /** @var ContainerInterface */
    protected $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function getPresenterForEntity(HasPresenterInterface $entity): PresenterInterface
    {
        $presenter = $this->container->get($entity->getPresenterService());
        $presenter->setEntity($entity);

        return $presenter;
    }
}
