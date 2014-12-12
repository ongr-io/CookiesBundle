<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Tests\Functional\Cookie\Model;

use ONGR\CookiesBundle\Cookie\Model\GenericCookie;

/**
 * Class GenericCookieTest.
 */
class GenericCookieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test general instantiation of GenericCookie.
     */
    public function testCookieCreation()
    {
        $cookie = new GenericCookie('testCookie');

        $this->assertEquals('testCookie', $cookie->getName());
    }

    /**
     * Test loaded value into GenericCookie.
     */
    public function testLoadValue()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');

        $this->assertEquals('someValue', $cookie->getValue(), 'Wrong value loaded');
        $this->assertTrue($cookie->isLoaded(), 'cookie should be loaded');
    }

    /**
     * Test if second call to GenericCookie::load() doesn't change the cookie.
     */
    public function testDoubleRawValue()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');
        $cookie->load('anotherValue');

        $this->assertEquals('someValue', $cookie->getValue());
        $this->assertEquals('someValue', $cookie->getRawValue());
    }

    /**
     * Test if GenericCookie::toCookie() works as expected.
     */
    public function testToCookie()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');

        $cookie = $cookie->toCookie();

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Cookie', $cookie);
        $this->assertEquals('someValue', $cookie->getValue());
    }

    /**
     * Test if GenericCookie::setValue() overrides GenericCookie::$rawValue.
     */
    public function testToCookieWithSetValue()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');
        $cookie->setValue('anotherValue');

        $cookie = $cookie->toCookie();

        $this->assertEquals('anotherValue', $cookie->getValue());
    }

    /**
     * Test getValue throws exception, if cookie value has not been loaded.
     */
    public function testGetValueBeforeInjection()
    {
        $this->setExpectedException('\ONGR\CookiesBundle\Exception\CookieNotLoadedException');

        $cookie = new GenericCookie('test');
        $cookie->getValue();
    }

    /**
     * Test setValue throws exception, if cookie value has not been loaded.
     */
    public function testSetValueBeforeInjection()
    {
        $this->setExpectedException('\ONGR\CookiesBundle\Exception\CookieNotLoadedException');

        $cookie = new GenericCookie('test');
        $cookie->setValue('someValue');
    }

    /**
     * Test if getValue returns null, if value is null (should not throw exception).
     */
    public function testNullValue()
    {
        $cookie = new GenericCookie('test');
        $cookie->load(null);
        $this->assertNull($cookie->getValue());
    }

    /**
     * Test cookie returns not dirty status, when not modified.
     */
    public function testNotDirtyWhenLoaded()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');
        $this->assertFalse($cookie->isDirty());
    }

    /**
     * Test cookie returns dirty status, when modified.
     */
    public function testDirtyWhenModified()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');
        $cookie->setValue('anotherValue');
        $this->assertTrue($cookie->isDirty());
    }

    /**
     * Test cookie returns not dirty status, when not modified.
     */
    public function testNotDirtyWhenModifiedWithSameValue()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');
        $cookie->setValue('someValue');
        $this->assertFalse($cookie->isDirty());
    }

    /**
     * Test can set clear flag.
     */
    public function testClear()
    {
        $cookie = new GenericCookie('test');
        $cookie->load('someValue');
        $cookie->setClear(true);
        $this->assertTrue($cookie->getClear());
    }

    /**
     * Test can set 'expires_interval' value and it overrides 'expires_time' value.
     */
    public function testInterval()
    {
        $cookie = new GenericCookie('test');
        $cookie->setDefaults(['expires_time' => 1000000000, 'expires_interval' => 'P5D']);
        $cookie->load('someValue');
        $this->assertGreaterThan(time() + 5 * 24 * 3600 - 5, $cookie->getExpiresTime());
        $this->assertLessThan(time() + 5 * 24 * 3600 + 5, $cookie->getExpiresTime());
    }

    /**
     * Test defaults setter and getter.
     */
    public function testDefaultsSetter()
    {
        $defaults = ['expires_time' => 1000000000, 'expires_interval' => 'P5D'];

        $cookie = new GenericCookie('test');
        $this->assertEquals([], $cookie->getDefaults());

        $cookie->setDefaults($defaults);
        $this->assertEquals($defaults, $cookie->getDefaults());
    }
}
