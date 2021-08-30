<?php

namespace Drupal\wmpresenter\Entity;

abstract class AbstractPresenter implements PresenterInterface
{
    protected $entity;

    public function __isset($prop)
    {
        return isset($this->entity->{$prop});
    }

    public function __get($prop)
    {
        return $this->entity->{$prop};
    }

    public function __call($method, array $args)
    {
        $methodNames = $this->methodNames($method);

        foreach ($methodNames as $methodName) {
            $call = [$this->entity, $methodName];
            if (is_callable($call)) {
                return call_user_func_array($call, $args);
            }
        }

        throw new \BadMethodCallException(sprintf(
            'Methods with names %s do not exist on %s or %s.',
            implode(', ', $methodNames),
            get_class($this),
            get_class($this->entity)
        ));
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    protected function methodNames($methodName)
    {
        $uc = ucfirst($methodName);
        return [$methodName, 'is' . $uc, 'get' . $uc, 'has' . $uc];
    }
}
