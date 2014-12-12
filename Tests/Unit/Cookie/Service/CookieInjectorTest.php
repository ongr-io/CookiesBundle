<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Tests\Functional\Cookie\Service;

use ONGR\CookiesBundle\Cookie\Service\CookieInjector;
use ONGR\CookiesBundle\Cookie\Model\GenericCookie;
use Symfony\Component\HttpFoundation\Request;

class CookieInjectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test CookieInjector:inject() to not throw exception when cookie is not found.
     */
    public function testNonExistentCookie()
    {
        $cookieInjector = new CookieInjector();
        $cookieModel = new GenericCookie('non_existet_cookie_name');
        $cookieInjector->addCookieModel($cookieModel);
        $cookieInjector->inject($this->createRequestMock());
    }

    /**
     * Test CookieInjector:inject() method.
     */
    public function testCookieInjection()
    {
        $cookieName = 'test';
        $cookieValue = 'test_value';

        $cookieInjector = new CookieInjector();
        $request = $this->createRequestMock($cookieName, $cookieValue);
        $cookieModel = new GenericCookie($cookieName);
        $cookieInjector->addCookieModel($cookieModel);
        $cookieInjector->inject($request);

        $this->assertEquals($cookieValue, $cookieModel->getValue());
    }

    /**
     * Test getCookieModels method.
     */
    public function testGetCookieModels()
    {
        $cookieInjector = new CookieInjector();
        $cookieName = 'test';
        $cookieModel = new GenericCookie($cookieName);
        $cookieInjector->addCookieModel($cookieModel);
        $this->assertCount(1, $cookieInjector->getCookieModels());
    }

    /**
     * Test setCookieModels method.
     */
    public function testSetCookieModels()
    {
        $cookieInjector = new CookieInjector();
        $cookieName = 'test';
        $cookieModel = new GenericCookie($cookieName);
        $cookieInjector->setCookieModels([$cookieModel]);
        $this->assertCount(1, $cookieInjector->getCookieModels());
    }

    /**
     * Creates mock of request.
     *
     * @param mixed $cookieName
     * @param mixed $cookieValue
     *
     * @return Request|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createRequestMock($cookieName = null, $cookieValue = null)
    {
        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');

        $mockBuilder = $this->getMockBuilder('\Symfony\Component\HttpFoundation\ParameterBag');

        if (null !== $cookieName) {
            $mockBuilder->setMethods(['get', 'has']);
        }

        $cookiesBag = $mockBuilder->getMock();

        if (null !== $cookieValue) {
            $cookiesBag
                ->expects($this->once())
                ->method('get')
                ->with($this->equalTo($cookieName))
                ->willReturn($cookieValue);

            $cookiesBag
                ->expects($this->once())
                ->method('has')
                ->with($this->equalTo($cookieName))
                ->willReturn(true);
        }

        $request->cookies = $cookiesBag;

        return $request;
    }

    /**
     * Creates mock of cookie.
     *
     * @param string $name
     * @param string $value
     *
     * @return GenericCookie|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCookieMock($name, $value)
    {
        /** @var GenericCookie $cookie */
        $cookie = $this->getMock(
            '\ONGR\CookiesBundle\Cookie\Model\GenericCookie',
            null,
            [$name]
        );
        $cookie->load($value);

        return $cookie;
    }
}
