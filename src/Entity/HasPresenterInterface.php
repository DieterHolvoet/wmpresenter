<?php

namespace Drupal\wmpresenter\Entity;

interface HasPresenterInterface
{
    /** @return string Name of the service that implements a PresenterInterface */
    public function getPresenterService();
}
