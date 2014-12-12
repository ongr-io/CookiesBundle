<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Tests;

/**
 * Example settings sets provider.
 */
class FooSettingsProvider
{
    /**
     * @return array
     */
    public function getFooSets()
    {
        return [
            'domains' => [
                'name' => 'Domains',
                'category' => 'category_1',
                'type' => [
                    'choice',
                    [
                        'expanded' => true,
                        'multiple' => true,
                        'choices' => [
                            'settings_set_foo' => 'Settings set foo',
                            'settings_set_bar' => 'Settings set bar',
                        ],
                    ],
                ],
            ],
        ];
    }
}
