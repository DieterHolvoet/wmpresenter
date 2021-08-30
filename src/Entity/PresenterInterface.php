<?php

namespace Drupal\wmpresenter\Entity;

interface PresenterInterface
{
    public function setEntity($entity): void;

    public function getEntity();
}
