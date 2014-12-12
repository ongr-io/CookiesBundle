<?php

namespace ONGR\CookiesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ONGR\CookiesBundle\DependencyInjection\Compiler\CookieCompilerPass;

/**
 * This class is used to register component into Symfony app kernel.
 */
class ONGRCookiesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CookieCompilerPass());
    }
}
