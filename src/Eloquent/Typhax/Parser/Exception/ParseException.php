<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser\Exception;

use Exception;
use LogicException;

abstract class ParseException extends LogicException
{
    /**
     * @param string    $message
     * @param integer   $position
     * @param Exception $previous
     */
    public function __construct($message, $position, Exception $previous = null)
    {
        $this->position = $position;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return integer
     */
    public function position()
    {
        return $this->position;
    }

    /**
     * @var integer
     */
    private $position;
}