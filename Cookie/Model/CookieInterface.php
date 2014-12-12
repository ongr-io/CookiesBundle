<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Cookie\Model;

use Symfony\Component\HttpFoundation\Cookie;

/**
 * Interface for cookie models.
 */
interface CookieInterface
{
    /**
     * Get cookie name.
     */
    public function getName();

    /**
     * Set cookie value.
     *
     * @param mixed $value
     *
     * @throws \ONGR\CookiesBundle\Exception\CookieNotLoadedException
     */
    public function setValue($value);

    /**
     * Get cookie value.
     *
     * @return mixed
     * @throws \ONGR\CookiesBundle\Exception\CookieNotLoadedException
     */
    public function getValue();

    /**
     * Load model data from HTTP cookie.
     *
     * @param string $cookieValue
     */
    public function load($cookieValue);

    /**
     * Convert current model to a HTTP cookie.
     *
     * @return Cookie
     * @throws \ONGR\CookiesBundle\Exception\CookieNotLoadedException
     */
    public function toCookie();

    /**
     * Return true, if cookie has been modified since the load.
     *
     * @return bool
     * @throws \ONGR\CookiesBundle\Exception\CookieNotLoadedException
     */
    public function isDirty();

    /**
     * Should the cookie be set as removed in the response.
     *
     * @return bool
     * @throws \ONGR\CookiesBundle\Exception\CookieNotLoadedException
     */
    public function getClear();

    /**
     * Set should the cookie be set as removed in the response.
     *
     * @param bool $clear
     *
     * @throws \ONGR\CookiesBundle\Exception\CookieNotLoadedException
     */
    public function setClear($clear);

    /**
     * Get if cookie value has been loaded from the request.
     *
     * @return bool
     */
    public function isLoaded();

    /**
     * @return string
     */
    public function getDomain();

    /**
     * @param string $domain
     */
    public function setDomain($domain);

    /**
     * @return string
     */
    public function getExpiresTime();

    /**
     * @param string $expiresTime
     */
    public function setExpiresTime($expiresTime);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @param string $path
     */
    public function setPath($path);

    /**
     * @return string
     */
    public function isHttpOnly();

    /**
     * @param string $isHttpOnly
     */
    public function setIsHttpOnly($isHttpOnly);

    /**
     * @return string
     */
    public function isSecure();

    /**
     * @param string $isSecure
     */
    public function setIsSecure($isSecure);

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @param array $defaults
     */
    public function setDefaults($defaults);
}
