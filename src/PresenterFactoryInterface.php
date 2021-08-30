<?php

namespace Drupal\wmpresenter;

use Drupal\wmpresenter\Entity\HasPresenterInterface;
use Drupal\wmpresenter\Entity\PresenterInterface;

interface PresenterFactoryInterface
{
    public function getPresenterForEntity(HasPresenterInterface $entity): PresenterInterface;
}
