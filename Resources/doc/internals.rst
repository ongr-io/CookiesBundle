Internals
=========

CookieBundle works by listening on Symfony's ``kernel.request`` and ``kernel.response`` events
and injecting (or updating) cookies.

Components
----------

1. ``CookieModelListener`` - event listener responsible for listening for ``kernel.request`` and ``kernel.response``
events;

2. ``CookieInjector`` - service doing the heavy lifting (gets and sets cookies).

3. ``Cookie Models`` all implementing ``CookieInterface`` via ``GenericCookie`` class. Basic cookie fields
(domain, expires, etc.) are placed in ``CookieFieldsTrait`` trait. ``Cookie Models`` are responsible for loading the raw
mixed data into an nice PHP object (implementation is customizable and may differ per-model) and returning cookie-izable
raw data when the need arises.


Flow
----

1. Symfony receives a request. ``kernel.request`` event is fired. ``CookieModelListener`` is listening.

2. ``CookieModelListener``'s  ``onKernelRequest`` method is called, ``GetResponseEvent`` is passed to it.
``onKernelRequest`` calls ``CookieInjector``'s ``inject`` method.

3. ``CookieInjector`` iterates through registered ``Cookie Models``, gets raw data for each one and calls a
``Cookie Model``'s ``load`` method to load the data from the cookie.

4. Now we have a nice cookie-based object available!

5. We do whatever we need to do,

6. Symfony prepares to return a response. ``kernel.response`` event is fired. Once again, ``CookieModelListener`` is listening.

7. ``CookieModelListener`` 's  ``onKernelResponse`` method is called, ``FilterResponseEvent`` is passed to it.
``onKernelResponse`` calls ``CookieInjector``'s ``update`` method.

8. ``CookieInjector`` iterates through cookies to be sent to the client, "flattens" them, and then iterates through
registered ``Cookie Models``, calling ``toCookie`` method to get the data to be stored in the cookie. If ``Cookie Model``'s
``clear`` property is set to true, the cookie is cleared, otherwise it is saved.

9. Our cookie is either saved and sent to the users' browser or cleared from it.

10. Everyone is happy.

Configuration of ``Cookie Models``
----------------------------------

``Cookie Models`` are described as any other Symfony service, with one significant difference: tag ``ongr_cookie.cookie``
is used to denote that the service is a ``Cookie Model``. All services tagged with this tag are collected in a separate
compiler pass and added to the ``ongr_cookie.injector`` service by appending ``addCookieModel`` call to its' definition.
