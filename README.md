CookiesBundle
=========

Cookies bundle provides cookie model abstraction tag.
It can be used as e Symfony service.
Please see How to work with cookies page.


[![Build Status](https://travis-ci.org/ongr-io/CookiesBundle.svg?branch=master)](https://travis-ci.org/ongr-io/CookiesBundle) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/ongr/cookies-bundle/downloads.svg)](https://packagist.org/packages/ongr/cookies-bundle)
[![Latest Stable Version](https://poser.pugx.org/ongr/cookies-bundle/v/stable.svg)](https://packagist.org/packages/ongr/cookies-bundle)
[![Latest Unstable Version](https://poser.pugx.org/ongr/cookies-bundle/v/unstable.svg)](https://packagist.org/packages/ongr/cookies-bundle)
[![License](https://poser.pugx.org/ongr/cookies-bundle/license.svg)](https://packagist.org/packages/ongr/cookies-bundle)

# Working with cookies

## How to define a cookie model

ONGR provides cookie model abstraction for working with cookie values in the request and response.

One can define a service:

```yaml
parameters:
    project.cookie_foo.name: cookie_foo
    project.cookie_foo.defaults: # Defaults section is optional
        http_only: false
        expires_interval: P5DT4H # 5 days and 4 hours

services:
    project.cookie_foo:
        class: %ongr_cookie.json.class%
        arguments: [ %project.cookie_foo.name% ]
        calls:
            - [setDefaults, [%project.cookie_foo.defaults%]] # Optional
        tags:
            - { name: ongr_cookie.cookie }
```

Such injected service allows accessing cookie value, and upon modification, will send new value back to the client browser (using [CookieModelListener] (https://github.com/ongr-io/CookiesBundle/blob/master/EventListener/CookieModelListener.php))

```php

class CookieController
{    
    use ContainerAwareTrait;

    public function updateAction()
    {
        /** @var JsonCookie $cookie */
        $cookie = $this->container->get('project.cookie_foo');
        $cookie->setValue(['bar']);
        $cookie->setExpiresTime(2000000000);

        return new JsonResponse();
    }
}
```

## Default values

Possible `setDefaults` keys (default values if unspecified):
- `domain` - string (null)
- `path` - string ('/')
- `http_only` - boolean (true)
- `secure` - boolean (false)
- `expires_time` - integer (0)
- `expires_interval` - [`DateInterval`](http://php.net/manual/en/dateinterval.construct.php) string (null)

These values are used to initialize the cookie model if cookie does not exist in client's browser.

## Model types

Currently, there are these preconfigured classes one can use:
- `%ongr_cookie.json.class%` - one can work with it's value as it was a PHP array. In the background, value is encoded and decoded back using JSON format.
- `%ongr_cookie.generic.class%` works with plain string data. Other cookie formats can be created by extending this class.

## Manually setting cookie

If a cookie with the same name, path and domain is added to the response object, it's value is not overwritten with the changed cookie model data.

## Deleting cookie

To remove a cookie from the client browser, use `$cookie->setClear(true)`. All other model values will be ignored.
