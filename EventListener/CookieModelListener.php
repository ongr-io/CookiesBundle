<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\EventListener;

use ONGR\CookiesBundle\Cookie\Service\CookieInjector;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Initiates cookie values injection to cookie models.
 */
class CookieModelListener
{
    /**
     * @var CookieInjector
     */
    protected $cookieInjector;

    /**
     * OnKernelRequest.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->cookieInjector->inject($event->getRequest());
    }

    /**
     * OnKernelResponse.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->cookieInjector->update($event->getResponse());
    }

    /**
     * @param CookieInjector $cookieInjector
     */
    public function setCookieInjector(CookieInjector $cookieInjector)
    {
        $this->cookieInjector = $cookieInjector;
    }
}
