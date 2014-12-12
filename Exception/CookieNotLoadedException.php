<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\CookiesBundle\Exception;

/**
 * Thrown when reading cookie model value, when value has not been loaded yet.
 */
class CookieNotLoadedException extends \LogicException
{
}
