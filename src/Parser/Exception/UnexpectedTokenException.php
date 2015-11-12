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
 * An unexpected token was encountered.
 */
final class UnexpectedTokenException extends AbstractParseException
{
    /**
     * Construct a new unexpected token exception.
     *
     * @param string        $unexpected The unexpected token name.
     * @param integer       $offset     The offset.
     * @param array<string> $expected   The list of expected token names.
     * @param Exception     $cause      The cause, if available.
     */
    public function __construct($unexpected, $offset, array $expected, Exception $cause = null)
    {
        $this->unexpected = $unexpected;
        $this->expected = $expected;

        if (count($expected) > 1) {
            $expected = 'one of ' . implode(', ', $expected);
        } else {
            $expected = array_pop($expected);
        }

        $message =
            'Unexpected ' .
            $unexpected .
            ' at offset ' .
            $offset .
            '. Expected ' .
            $expected .
            '.';

        parent::__construct($message, $offset, $cause);
    }

    /**
     * Get the unexpected token name.
     *
     * @return string The unexpected token name.
     */
    public function unexpected()
    {
        return $this->unexpected;
    }

    /**
     * Get the expected token names.
     *
     * @return array<string> The expected token names.
     */
    public function expected()
    {
        return $this->expected;
    }

    private $unexpected;
    private $expected;
}
