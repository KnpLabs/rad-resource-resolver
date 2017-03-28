# Knp Rad Resource Resolver

[![Build Status](https://travis-ci.org/KnpLabs/rad-resource-resolver.svg?branch=master)](https://travis-ci.org/KnpLabs/rad-resource-resolver)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KnpLabs/rad-resource-resolver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KnpLabs/rad-resource-resolver/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/knplabs/rad-resource-resolver/v/stable)](https://packagist.org/packages/knplabs/rad-resource-resolver) [![Total Downloads](https://poser.pugx.org/knplabs/rad-resource-resolver/downloads)](https://packagist.org/packages/knplabs/rad-resource-resolver) [![Latest Unstable Version](https://poser.pugx.org/knplabs/rad-resource-resolver/v/unstable)](https://packagist.org/packages/knplabs/rad-resource-resolver) [![License](https://poser.pugx.org/knplabs/rad-resource-resolver/license)](https://packagist.org/packages/knplabs/rad-resource-resolver)

## Why using it?
Tired of doing the same things again and again in your controllers, like transforming a URL value in an object?
Don't want to use ParamConverter Annotations?

This Resource Resolver is for you.

## Installation

With composer :

```bash
$ composer require knplabs/rad-resource-resolver
```

If you are using symfony2 you can update your `app/AppKernel.php` file:

```php
public function registerBundles()
{
    $bundles = array(
        // bundles here ...
        new Knp\Rad\ResourceResolver\Bundle\ResourceResolverBundle(),
    );
}
```
 
## How to use it?

In a yaml routing file, it could look like this :

```yaml
    users_show:
        path: /users/{id}
        defaults:
            _resources:
                user:
                    service: my.user.repository
                    method: find
                    arguments: [$id]
    # This will automatically resolve the resource to give you a $user object in your request attributes
```
        
```yaml
    countries_cities_buildings_index:
        path: /countries/{countryId}/cities/{citySlug}/buildings
        defaults:
            _resources:
                buildings:
                    service: app.building.repository
                    method: findByCountryAndCityAndActivity
                    arguments: [$countryId, $citySlug, "School"]
    # You will have a $buildings variable in your request attributes
```

Every `key` under `_resources` will be return as a `$key` converted value in your request attributes.

However, you can use more concise ways to express your resources configuration :

```yaml
    product_show:
        path: /product/{slug}
        defaults:
            _resources:
                product: [ "my.product.repository:findBySlug", [ $slug ] ]
                bestOffers: "my.product.repository.offer:findBestOffers"
                bestSellers: "my.product.reposutory.sellers:findBestSellers"

```

## Optional Resources

By default, the Rad Resource Resolver throws a `Symfony\Component\HttpKernel\Exception\NotFoundHttpException` if the resource was not found. You can override this behavior by adding the `required` option to false:

```yaml
    _resources:
        buildings:
            service: app.building.repository
            method: findByCountryAndCityAndActivity
            arguments: [$countryId, $citySlug, "School"]
            required: false
```

## Available resource resolving arguments

- URL variables: you have to use the `$` prefix. For example, if your URL is `/products/{products}/` you can access to `product` value by using `$product`.
- Services: you can use the `@` prefix (ex: @doctrine)
- Previously resolved resources: you can use the `&` prefix (ex: `&user` will return the `user` resource)

## How does it work?

A `ResourcesListener` listens to `kernel.controller` event and resolves automatically all resources in `_resources`.
The component uses `ParameterCaster` objects to catch different argument types and `Parser` objects to resolve _resources locations syntax.

This means you can easily add your own `ParameterCasters` and `Parsers` to change the syntax used by the component.

To add your own `ParameterCaster`, just tag it with `knp_rad_resource_resolver.parameter_caster`.
The tag to add a `Parser` is `knp_rad_resource_resolver.parser`.

#Events

All events are listed [here](./src/Knp/Rad/ResourceResolver/Events.php).

##How can I hook resource resolution ?

There is two events : 

    - knp_rad_resource_resolver.before_resource_resolved:  dispatched before the resolution. You can set the resource before the resolution.
    - knp_rad_resource_resolver.resource_resolved:         dispatched after the resolution.

##How can I get all resolved resources ?

There is a service alias named `knp_rad_resource_resolver.resource_container` where you can get all resolved resources. You can also listen to the event `knp_rad_resource_resolver.resource.added` and be notified when a resource is added to the container.

## License
This project is published under MIT License. Feel free to contribute.

