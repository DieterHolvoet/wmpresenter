Entity Presenter
======================

[![Latest Stable Version](https://poser.pugx.org/wieni/wmpresenter/v/stable)](https://packagist.org/packages/wieni/wmpresenter)
[![Total Downloads](https://poser.pugx.org/wieni/wmpresenter/downloads)](https://packagist.org/packages/wieni/wmpresenter)
[![License](https://poser.pugx.org/wieni/wmpresenter/license)](https://packagist.org/packages/wieni/wmpresenter)

> Adds support for creating & injecting view presenters on top of entity classes

## Why?
Presenters are a principle taken from 
[Model-view-presenter](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93presenter), a software design pattern 
similar to [Model-view-controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller). We use it to 
transform data before displaying it. Some example use cases:
- Concatenating names and prefixes/suffixes into a person's full title
- Displaying a fallback image in case an image field is empty
- Converting a set of opening hours to a format that's easier to consume in Twig

## Installation
This package requires PHP 7.1 and Drupal 8 or higher. It can be
installed using Composer:

```bash
 composer require wieni/wmpresenter
```

## How does it work?
### Creating presenters
Presenter classes should implement [`PresenterInterface`](src/Entity/PresenterInterface.php).

[`AbstractPresenter`](src/Entity/AbstractPresenter.php) is the recommended base class, it provides magic methods 
allowing you to call methods of the entity class directly on the presenter class. The `@mixin` docblock can be used to 
tell IDE's about this behaviour. The `@property` docblock can be used if you don't like magic and prefer to call the 
entity's methods directly on the entity.

```php
<?php

namespace Drupal\wmcustom\Entity\Presenter\Node;

use Drupal\wmcustom\Entity\Model\Node\Page as PageModel;
use Drupal\wmpresenter\Entity\AbstractPresenter;

/**
 * @mixin PageModel
 * @property PageModel $entity
 */
class Page extends AbstractPresenter
{
}
```

Presenters should be registered as services. It's important to set `shared: false` on the presenter service, otherwise 
all presenters of the same type will work with the same entity.

Presenters can be assigned to entities by making the entity class implement 
[`HasPresenterInterface`](src/Entity/HasPresenterInterface.php). The `getPresenterService` method should return the 
presenter service ID.

Entities having presenters don't have to implement `EntityInterface`. Any class can be used. 

### Automatically injecting presenters
Entities are automatically converted to their presenter counterparts when including them in a Twig template. 
Some examples:
- The entity is passed as argument to the `view` method of [wmcontroller](https://github.com/wieni/wmcontroller) 
  controllers.
- The entity is passed to other Twig components using functionalities like `include` or `embed`.

### Manually loading presenters
In code, presenters can be loaded using
[`PresenterFactoryInterface::getPresenterForEntity`](src/PresenterFactoryInterface.php). 

In Twig, presenters can be loaded by passing the entity through the `p` or `presenter` filters. When passing an array
of entities, all entities will be converted to their presenter counterparts.

### Twig\Sandbox\SecurityError: Calling method on a _\<presenter\>_ object is not allowed
Twig has a whitelist feature that prevent people from calling methods on unknown classes in Twig templates. In order to
allow you to use presenters in Twig templates, you'll have to change the whitelist by adding the following to your 
`settings.php`:

```php
$settings['twig_sandbox_whitelisted_classes'] = [
    \Drupal\wmpresenter\Entity\PresenterInterface::class,
];
```

## Changelog
All notable changes to this project will be documented in the
[CHANGELOG](CHANGELOG.md) file.

## Security
If you discover any security-related issues, please email
[security@wieni.be](mailto:security@wieni.be) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE) file
for more information.
