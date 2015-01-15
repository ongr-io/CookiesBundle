=============
CookiesBundle
=============

Cookies bundle provides Symfony way to handle cookies defining them as services.

.. image:: https://travis-ci.org/ongr-io/CookiesBundle.svg?branch=master
    :target: https://travis-ci.org/ongr-io/CookiesBundle

.. image:: https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/badges/quality-score.png?b=master
    :target: https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/?branch=master

.. image:: https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/badges/coverage.png?b=master
    :target: https://scrutinizer-ci.com/g/ongr-io/CookiesBundle/?branch=master

.. image:: https://poser.pugx.org/ongr/cookies-bundle/downloads.svg
    :target: https://packagist.org/packages/ongr/cookies-bundle

.. image:: https://poser.pugx.org/ongr/cookies-bundle/v/stable.svg
    :target: https://packagist.org/packages/ongr/cookies-bundle

.. image:: https://poser.pugx.org/ongr/cookies-bundle/v/unstable.svg
    :target: https://packagist.org/packages/ongr/cookies-bundle

.. image:: https://poser.pugx.org/ongr/cookies-bundle/license.svg
    :target: https://packagist.org/packages/ongr/cookies-bundle

=============

Usage example in code:

.. code-block:: php

    class CookieController
    {
        public function readAction()
        {
            $cart = $this->container->get('project.cookie.cart')->getValue();
            $items = $cartCookie['items'];
            // ...
        }
    }
..

Cookie configuration example:

.. code-block:: yaml

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
..


Documentation
~~~~~~~~~~~~~

Documentation for this bundle can be found
`here <http://ongr.readthedocs.org/en/latest/sources/CookiesBundle.git/Resources/doc/index.html>`_


License
~~~~~~~

This bundle is under the MIT license. Please, see the complete license in the bundle `LICENSE </LICENSE>`_ file.
