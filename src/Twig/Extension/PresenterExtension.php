<?php

namespace Drupal\wmpresenter\Twig\Extension;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Render\RendererInterface;
use Drupal\wmpresenter\Entity\HasPresenterInterface;
use Drupal\wmpresenter\PresenterFactoryInterface;
use Twig_SimpleFilter;

class PresenterExtension extends \Twig_Extension
{
    /** @var PresenterFactoryInterface */
    protected $presenterFactory;
    /** @var RendererInterface */
    protected $renderer;

    public function __construct(
        PresenterFactoryInterface $presenterFactory,
        RendererInterface $renderer
    ) {
        $this->presenterFactory = $presenterFactory;
        $this->renderer = $renderer;
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('presenter', [$this, 'getPresenter']),
            new Twig_SimpleFilter('p', [$this, 'getPresenter']),
        ];
    }

    public function getPresenter($entities)
    {
        if (!is_array($entities)) {
            return $this->fetchPresenter($entities);
        }

        $presenters = [];
        foreach ($entities as $key => $entity) {
            $presenters[$key] = $this->fetchPresenter($entity);
        }

        return $presenters;
    }

    protected function fetchPresenter($entity)
    {
        if ($entity instanceof CacheableDependencyInterface) {
            $build = [];
            CacheableMetadata::createFromObject($entity)
                ->applyTo($build);
            $this->renderer->render($build);
        }

        if ($entity instanceof HasPresenterInterface) {
            return $this->presenterFactory->getPresenterForEntity($entity);
        }

        return $entity;
    }
}
