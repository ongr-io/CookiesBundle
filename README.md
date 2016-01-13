# CookiesBundle

Cookies bundle provides Symfony way to handle cookies defining them as services.

[![Build Status](https://travis-ci.org/ongr-io/CookiesBundle.svg?branch=master)](https://travis-ci.org/ongr-io/CookiesBundle)
[![Coverage Status](https://coveralls.io/repos/ongr-io/CookiesBundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/ongr-io/CookiesBundle?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ongr/cookies-bundle/v/stable)](https://packagist.org/packages/ongr/cookies-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/?branch=master)

## Install bundle
   
To install this bundle add it to composer.

```bash

   composer require ongr/cookies-bundle

```

Then register it in `AppKernel.php`

```php

   class AppKernel extends Kernel
   {
       public function registerBundles()
       {
           return [
               // ...
               new ONGR\CookiesBundle\ONGRCookiesBundle(),
           ];
       }

       // ...
   }

```

That's it - the bundle is ready for work.


## Simple usage example

```php

    class CookieController
    {
        public function readAction()
        {
            $cart = $this->container->get('project.cookie.cart')->getValue();
            $items = $cartCookie['items'];
            // ...
        }
    }
```

Cookie configuration example:

```yaml

    parameters:
        # One can optionally override defaults.
        project.cookie.cart.defaults:
            http_only: false
            expires_interval: P5DT4H # 5 days and 4 hours
    
    services:
        project.cookie.cart:
            class: %ongr_cookie.json.class%
            arguments: [ "project_cart" ]
            calls:
                - [ setDefaults, [ %project.cookie_foo.defaults% ] ]
            tags:
                - { name: ongr_cookie.cookie }
```

## Working with cookies

### How to define a cookie model

ONGR provides cookie model abstraction for working with cookie values in the request and response.

One can define a service:

```yaml

    parameters:
        project.cookie.foo.name: cookie.foo
        project.cookie.foo.defaults: # Defaults section is optional
            http_only: false
            expires_interval: P5DT4H # 5 days and 4 hours

    services:
        project.cookie.foo:
            class: %ongr_cookie.json.class%
            arguments: [ %project.cookie.foo.name% ]
            calls:
                - [setDefaults, [%project.cookie.foo.defaults%]] # Optional
            tags:
                - { name: ongr_cookie.cookie }

```

Such injected service allows accessing cookie value. If the value has been modified by your code, it will send new value back to the client browser.

```php

    class CookieController
    {
        use ContainerAwareTrait;

        public function updateAction()
        {
            /** @var JsonCookie $cookie */
            $cookie = $this->container->get('project.cookie.foo');
            $cookie->setValue(['bar']);
            // Cookie has been marked as dirty and will be updated in the response.
            $cookie->setExpiresTime(2000000000);

            return new JsonResponse();
        }
    }

```

### Default values

Possible `setDefaults` keys (default values if unspecified):

- `domain` - string (null)

- `path` - string ('/')

- `http_only` - boolean (true)

- `secure` - boolean (false)

- `expires_time` - integer (0)

- `expires_interval` - [DateInterval](http://php.net/manual/en/dateinterval.construct.php) string (null)

These values are used to initialize the cookie model if cookie does not exist in client's browser.

### Model types

Currently, there are these pre-configured classes one can use:

- `%ongr_cookie.json.class%` - one can work with it's value as it was a PHP array. In the background, value is encoded and decoded back using JSON format.

- `%ongr_cookie.generic.class%` works with plain string data. Other cookie formats can be created by extending this class.

### Manually setting cookie

If a cookie with the same name, path and domain is added to the response object, it's value is not overwritten with the changed cookie model data.

### Deleting cookie

To remove a cookie from the client browser, use `$cookie->setClear(true)`. All other model values will be ignored.


## Components

1. `CookieModelListener` - event listener responsible for listening for ``kernel.request`` and ``kernel.response``
events;

2. `CookieInjector` - service doing the heavy lifting (gets and sets cookies).

3. `Cookie Models` all implementing `CookieInterface` via `GenericCookie` class. Basic cookie fields
(domain, expires, etc.) are placed in `CookieFieldsTrait` trait. `Cookie Models` are responsible for loading the raw
mixed data into an nice PHP object (implementation is customizable and may differ per-model) and returning cookie-izable
raw data when the need arises.


## How it works?

1. Symfony receives a request. `kernel.request` event is fired. `CookieModelListener` is listening.

2. `CookieModelListener`'s  `onKernelRequest` method is called, `GetResponseEvent` is passed to it.
``onKernelRequest`` calls ``CookieInjector``'s ``inject`` method.

3. `CookieInjector` iterates through registered `Cookie Models`, gets raw data for each one and calls a
`Cookie Model`'s ``load`` method to load the data from the cookie.

4. Now we have a nice cookie-based object available!

5. We do whatever we need to do,

6. Symfony prepares to return a response. `kernel.response` event is fired. Once again, `CookieModelListener` is listening.

7. `CookieModelListener` 's  `onKernelResponse` method is called, `FilterResponseEvent` is passed to it.
`onKernelResponse` calls `CookieInjector`'s `update` method.

8. `CookieInjector` iterates through cookies to be sent to the client, "flattens" them, and then iterates through
registered `Cookie Models`, calling `toCookie` method to get the data to be stored in the cookie. If `Cookie Model`'s
`clear` property is set to true, the cookie is cleared, otherwise it is saved.

9. Our cookie is either saved and sent to the users' browser or cleared from it.

10. Everyone is happy.


## Configuration of `Cookie Models`

`Cookie Models` are described as any other Symfony service, with one significant difference: tag `ongr_cookie.cookie`
is used to denote that the service is a `Cookie Model`. All services tagged with this tag are collected in a separate
compiler pass and added to the `ongr_cookie.injector` service by appending `addCookieModel` call to its' definition.

Cookie models' names of a cookie service should not contain dot symbol '.' and must be the same as cookie names that need
to be modeled.


## License

This bundle is under the MIT license. Please, see the complete license in the bundle `LICENSE` file.
