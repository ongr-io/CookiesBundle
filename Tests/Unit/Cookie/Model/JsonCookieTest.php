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

use ONGR\CookiesBundle\Cookie\Model\JsonCookie;

/**
 * Class FlashBagCookieTest.
 */
class JsonCookieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test loaded value into FlashBagCookie.
     */
    public function testLoadValue()
    {
        $messages = ['message 1', 'message 2'];

        $cookie = new JsonCookie('test');
        $cookie->load(json_encode($messages));

        $this->assertEquals($messages, $cookie->getValue());
    }

    /**
     * Test processed value in FlashBagCookie.
     */
    public function testToCookie()
    {
        $messages = json_encode(['message 1', 'message 2']);

        $cookie = new JsonCookie('test');
        $cookie->load($messages);

        $cookie = $cookie->toCookie();

        $this->assertEquals($messages, $cookie->getValue());
    }

    /**
     * Test cookie returns not dirty status, when not modified.
     */
    public function testNotDirtyWhenLoaded()
    {
        $cookie = new JsonCookie('test');
        $cookie->load(json_encode(['test']));
        $this->assertFalse($cookie->isDirty());
    }
}
