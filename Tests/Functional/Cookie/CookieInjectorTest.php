<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Tests\Integration\Cookie;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

/**
 * Tests cookie value setting to cookie models.
 */
class CookieInjectorTest extends WebTestCase
{
    /**
     * If cookie model has been defined, it must have cookie value set.
     */
    public function testInject()
    {
        $path = '/cookie/read';
        $client = $this->makeRequest($path);
        $this->assertSame('["foo"]', $client->getResponse()->getContent());
    }

    /**
     * If cookie value has been modified, modified value should be sent to the browser.
     */
    public function testModify()
    {
        $path = '/cookie/update';
        $client = $this->makeRequest($path);

        $cookies = $client->getCookieJar()->all();
        $this->assertCount(1, $cookies);
        $cookie = $cookies[0];
        $this->assertJsonStringEqualsJsonString('["bar"]', $cookie->getValue());
        $this->assertSame(false, $cookie->isHttpOnly());
        $this->assertSame(2000000000, $cookie->getExpiresTime());
    }

    /**
     * If cookie has been cleared in the model, cookie must no longer exist in browser.
     */
    public function testClear()
    {
        $path = '/cookie/clear';
        $client = $this->makeRequest($path);

        $cookies = $client->getCookieJar()->all();
        $this->assertCount(0, $cookies);
    }

    /**
     * Perform request with one cookie set.
     *
     * @param string $path
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function makeRequest($path)
    {
        $client = static::createClient();
        $cookie = new Cookie('cookie_foo', json_encode(['foo']));
        $client->getCookieJar()->set($cookie);
        $client->request('GET', $path);
        $this->assertTrue($client->getResponse()->isOk());

        return $client;
    }
}
