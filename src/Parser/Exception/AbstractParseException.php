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

use Exception;

/**
 * An abstract base class for implementing parse exceptions.
 */
abstract class AbstractParseException extends Exception implements
    ParseException
{
    /**
     * Construct a new parse exception.
     *
     * @param string    $message The message.
     * @param integer   $offset  The offset.
     * @param Exception $cause   The cause, if available.
     */
    public function __construct($message, $offset, Exception $cause = null)
    {
        $this->offset = $offset;

        parent::__construct($message, 0, $cause);
    }

    /**
     * Get the offset.
     *
     * @return integer The offset.
     */
    public function offset()
    {
        return $this->offset;
    }

    private $offset;
}
