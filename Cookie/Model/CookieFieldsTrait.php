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

/**
 * Field, getters and setters boilerplate for CookieInterface.
 *
 * @see CookieInterface
 */
trait CookieFieldsTrait
{
    /**
     * @var string Name of the cookie.
     */
    protected $name;

    /**
     * @var string
     */
    protected $domain = '';

    /**
     * @var int
     */
    protected $expiresTime = 0;

    /**
     * @var string
     */
    protected $path = '/';

    /**
     * @var bool
     */
    protected $isHttpOnly = true;

    /**
     * @var bool
     */
    protected $isSecure = false;

    /**
     * @var array Array of default cookie properties, when no cookie is found in the response.
     */
    protected $defaults = [];

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return int
     */
    public function getExpiresTime()
    {
        return $this->expiresTime;
    }

    /**
     * @param int $expiresTime
     */
    public function setExpiresTime($expiresTime)
    {
        $this->expiresTime = $expiresTime;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function isHttpOnly()
    {
        return $this->isHttpOnly;
    }

    /**
     * @param bool $isHttpOnly
     */
    public function setIsHttpOnly($isHttpOnly)
    {
        $this->isHttpOnly = $isHttpOnly;
    }

    /**
     * @return bool
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * @param bool $isSecure
     */
    public function setIsSecure($isSecure)
    {
        $this->isSecure = $isSecure;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }
}
