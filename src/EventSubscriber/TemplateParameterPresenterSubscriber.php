<?php

namespace Drupal\wmpresenter\EventSubscriber;

use Drupal\wmpresenter\Entity\HasPresenterInterface;
use Drupal\wmpresenter\PresenterFactoryInterface;
use Drupal\wmtwig\Event\TemplateParameterEvent;
use Drupal\wmtwig\WmTwigEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TemplateParameterPresenterSubscriber implements EventSubscriberInterface
{
    /** @var PresenterFactoryInterface */
    protected $factory;

    public function __construct(
        PresenterFactoryInterface $factory
    ) {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents(): array
    {
        $events[WmTwigEvents::TEMPLATE_PARAMETER][] = ['onTemplateParameter'];

        return $events;
    }

    public function onTemplateParameter(TemplateParameterEvent $event): void
    {
        $value = $event->getValue();

        if (is_array($value) && reset($value) instanceof HasPresenterInterface) {
            foreach ($value as $subKey => $subValue) {
                if ($subValue instanceof HasPresenterInterface) {
                    $value[$subKey] = $this->factory->getPresenterForEntity($subValue);
                }
            }

            $event->setValue($value);
            return;
        }

        if (!$value instanceof HasPresenterInterface) {
            return;
        }

        $event->setValue($this->factory->getPresenterForEntity($value));
    }
}
