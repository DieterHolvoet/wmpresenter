<?php

namespace Drupal\wmpresenter\Twig\Extension;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Render\RendererInterface;
use Drupal\wmpresenter\Entity\HasPresenterInterface;
use Drupal\wmpresenter\PresenterFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PresenterExtension extends AbstractExtension
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

    public function getFilters(): array
    {
        return [
            new TwigFilter('presenter', [$this, 'getPresenter']),
            new TwigFilter('p', [$this, 'getPresenter']),
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
