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

final class UnexpectedTokenException extends ParseException
{
    /**
     * @param string        $unexpected
     * @param integer       $position
     * @param array<string> $expected
     * @param Exception     $previous
     */
    public function __construct($unexpected, $position, array $expected, Exception $previous = null)
    {
        $this->unexpected = $unexpected;
        $this->expected = $expected;

        if (count($expected) > 1) {
            $expected = 'one of '.implode(', ', $expected);
        } else {
            $expected = array_pop($expected);
        }

        $message =
            'Unexpected '.
            $unexpected.
            ' at position '.
            $position.
            '. Expected '.
            $expected.
            '.'
        ;

        parent::__construct($message, $position, $previous);
    }

    /**
     * @return string
     */
    public function unexpected()
    {
        return $this->unexpected;
    }

    /**
     * @return array<string>
     */
    public function expected()
    {
        return $this->expected;
    }

    private $unexpected;
    private $expected;
}
