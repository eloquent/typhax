<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser\Exception;

/**
 * The interface implemented by parse exceptions.
 */
interface ParseException
{
    /**
     * Get the offset.
     *
     * @return integer The offset.
     */
    public function offset();
}
