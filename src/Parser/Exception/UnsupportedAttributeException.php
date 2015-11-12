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
 * An unsupported attribute was encountered.
 */
final class UnsupportedAttributeException extends AbstractParseException
{
    /**
     * Construct a new unsupported attribute exception.
     *
     * @param string    $typeName  The type name.
     * @param string    $attribute The attribute.
     * @param integer   $offset    The offset.
     * @param Exception $cause     The cause, if available.
     */
    public function __construct(
        $typeName,
        $attribute,
        $offset,
        Exception $cause = null
    ) {
        $this->typeName = $typeName;
        $this->attribute = $attribute;

        $message =
            'Unsupported attribute at offset ' .
            $offset .
            ". Type '" .
            $typeName .
            "' does not support attribute '" .
            $attribute .
            "'.";

        parent::__construct($message, $offset, $cause);
    }

    /**
     * Get the type name.
     *
     * @return string The type name.
     */
    public function typeName()
    {
        return $this->typeName;
    }

    /**
     * Get the attribute.
     *
     * @return string The attribute.
     */
    public function attribute()
    {
        return $this->attribute;
    }

    private $typeName;
    private $attribute;
}
