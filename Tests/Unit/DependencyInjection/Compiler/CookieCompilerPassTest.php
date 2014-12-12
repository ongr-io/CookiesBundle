<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Tests\Functional\DependencyInjection\Compiler;

use ONGR\CookiesBundle\DependencyInjection\Compiler\CookieCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class CookieCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test process method.
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->setDefinition('ongr_cookie.injector', new Definition());

        $definition = new Definition();
        $definition->addTag('ongr_cookie.cookie');
        $container->setDefinition('cookie_foo', $definition);

        $pass = new CookieCompilerPass();
        $pass->process($container);
        $calls = $container->getDefinition('ongr_cookie.injector')->getMethodCalls();
        $this->assertCount(1, $calls);
    }
}
