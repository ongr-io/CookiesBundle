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
 * Cookie class model to deal with cookies encoded in JSON.
 */
class JsonCookie extends GenericCookie
{
    /**
     * {@inheritdoc}
     */
    protected function decode($rawValue)
    {
        $value = json_decode($rawValue, true);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function encode($value)
    {
        $rawValue = json_encode($value);

        return $rawValue;
    }
}
