<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Cookie\Service;

use ONGR\CookiesBundle\Cookie\Model\CookieInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Injects HTTP cookie into cookie models.
 */
class CookieInjector
{
    /**
     * @var CookieInterface[]
     */
    protected $cookieModels;

    /**
     * Inject HTTP cookies into cookie models.
     *
     * @param Request $request
     */
    public function inject(Request $request)
    {
        foreach ($this->cookieModels as $cookieModel) {
            $cookie = $this->getHttpCookie($cookieModel, $request);
            $cookieModel->load($cookie);
        }
    }

    /**
     * Get HTTP cookie for $model.
     *
     * @param CookieInterface $cookieModel
     * @param Request         $request
     *
     * @return array|null
     */
    protected function getHttpCookie(CookieInterface $cookieModel, Request $request)
    {
        $modelName = $cookieModel->getName();

        if (null === $request->cookies || !$request->cookies->has($modelName)) {
            return null;
        }

        $cookie = $request->cookies->get($modelName);

        return $cookie;
    }

    /**
     * @param \ONGR\CookiesBundle\Cookie\Model\CookieInterface[] $cookieModels
     */
    public function setCookieModels(array $cookieModels)
    {
        $this->cookieModels = $cookieModels;
    }

    /**
     * @param CookieInterface $cookieModel
     */
    public function addCookieModel(CookieInterface $cookieModel)
    {
        $this->cookieModels[] = $cookieModel;
    }

    /**
     * @return \ONGR\CookiesBundle\Cookie\Model\CookieInterface[]
     */
    public function getCookieModels()
    {
        return $this->cookieModels;
    }

    /**
     * Add modified or removed cookies to the response object.
     *
     * @param Response $response
     */
    public function update(Response $response)
    {
        $flatCookies = $response->headers->getCookies();
        $cookies = [];

        $getCookieId = function (Cookie $cookie) {
            return $cookie->getDomain() . '|' . $cookie->getPath() . '|' . $cookie->getName();
        };

        /** @var Cookie $cookie */
        foreach ($flatCookies as $cookie) {
            $cookies[$getCookieId($cookie)] = $cookie;
        }

        foreach ($this->cookieModels as $cookieModel) {
            $cookie = $cookieModel->toCookie();

            if ($cookieModel->getClear()) {
                $response->headers->clearCookie($cookie->getName(), $cookie->getPath(), $cookie->getDomain());
            } elseif (!isset($cookies[$getCookieId($cookie)]) && $cookieModel->isDirty()) {
                $response->headers->setCookie($cookieModel->toCookie());
            }
        }
    }
}
