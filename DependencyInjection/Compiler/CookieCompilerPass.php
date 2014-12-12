<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Finds all services tagged with 'cookie' tag and adds them to cookie factory.
 */
class CookieCompilerPass implements CompilerPassInterface
{
    /**
     * Finds all services tagged with 'cookie' tag and adds them to cookie factory.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('ongr_cookie.cookie');
        $injectorDefinition = $container->getDefinition('ongr_cookie.injector');

        foreach ($taggedServices as $id => $tagAttributes) {
            $injectorDefinition->addMethodCall('addCookieModel', [new Reference($id)]);
        }
    }
}
