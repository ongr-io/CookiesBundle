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

use ONGR\CookiesBundle\Exception\CookieNotLoadedException;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Generic cookie class model to deal with cookies.
 */
class GenericCookie implements CookieInterface
{
    use CookieFieldsTrait;

    /**
     * @var string Raw cookie value.
     */
    protected $rawValue;

    /**
     * @var mixed Parsed value of the cookie.
     */
    protected $value;

    /**
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * @var bool
     */
    protected $shouldClear = false;

    /**
     * @var string Hash of the raw value generated once on load.
     */
    protected $valueHash;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->ensureLoaded();
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $this->ensureLoaded();

        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function load($rawValue)
    {
        if ($this->hasRawValue()) {
            return;
        }

        $this->isLoaded = true;
        $this->rawValue = $rawValue;

        $defaults = [
            'domain' => null,
            'path' => '/',
            'http_only' => true,
            'secure' => false,
            'expires_time' => 0,
        ];
        $defaults = array_merge($defaults, $this->defaults);
        $this->setDomain($defaults['domain']);

        $expiresTime = $defaults['expires_time'];
        if (isset($this->defaults['expires_interval'])) {
            $expiresTime = (new \DateTime())
                ->add(new \DateInterval($this->defaults['expires_interval']))
                ->getTimestamp();
        }
        $this->setExpiresTime($expiresTime);

        $this->setIsHttpOnly($defaults['http_only']);
        $this->setIsSecure($defaults['secure']);
        $this->setPath($defaults['path']);

        $this->setValue($this->decode($this->rawValue));
        $this->loadHash();

        $this->afterLoad();
    }

    /**
     * Override this for the ability to modify value after loading.
     *
     * E.g. set expiration time dynamically.
     */
    protected function afterLoad()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * Check if model already has loaded cookie.
     *
     * @return bool
     */
    public function hasRawValue()
    {
        return (null !== $this->rawValue);
    }

    /**
     * Return value of $rawValue.
     *
     * @return string
     */
    public function getRawValue()
    {
        $this->ensureLoaded();

        return $this->rawValue;
    }

    /**
     * {@inheritdoc}
     */
    public function toCookie()
    {
        $this->rawValue = $this->encode($this->getValue());

        return new Cookie(
            $this->getName(),
            $this->getRawValue(),
            $this->getExpiresTime(),
            $this->getPath(),
            $this->getDomain(),
            $this->isSecure(),
            $this->isHttpOnly()
        );
    }

    /**
     * Build $rawValue from data in $value.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function encode($value)
    {
        return $value;
    }

    /**
     * Decode $value from $rawValue.
     *
     * @param string $rawValue
     *
     * @return mixed
     */
    protected function decode($rawValue)
    {
        return $rawValue;
    }

    /**
     * {@inheritdoc}
     */
    public function isDirty()
    {
        return ($this->calculateHash() !== $this->valueHash);
    }

    /**
     * Calculate hash of cookie data.
     *
     * @return string
     */
    protected function calculateHash()
    {
        return md5((string)$this->toCookie());
    }

    /**
     * {@inheritdoc}
     */
    public function getClear()
    {
        $this->ensureLoaded();

        return $this->shouldClear;
    }

    /**
     * {@inheritdoc}
     */
    public function setClear($clear)
    {
        $this->ensureLoaded();
        $this->shouldClear = $clear;
    }

    /**
     * Store hash of the cookie value for dirty detection.
     */
    protected function loadHash()
    {
        $this->valueHash = $this->calculateHash();
    }

    /**
     * Throw exception, if cookie has not been loaded yet.
     *
     * @throws CookieNotLoadedException
     */
    protected function ensureLoaded()
    {
        if (!$this->isLoaded) {
            throw new CookieNotLoadedException();
        }
    }
}
