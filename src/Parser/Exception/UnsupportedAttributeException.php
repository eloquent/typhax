<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser\Exception;

use Exception;

final class UnsupportedAttributeException extends ParseException
{
    /**
     * @param string    $typeName
     * @param string    $attribute
     * @param integer   $position
     * @param Exception $previous
     */
    public function __construct(
        $typeName,
        $attribute,
        $position,
        Exception $previous = null
    ) {
        $this->typeName = $typeName;
        $this->attribute = $attribute;

        parent::__construct(
            sprintf(
                'Unsupported attribute at position %d. ' .
                    'Type %s does not support attribute %s.',
                $position,
                var_export($typeName, true),
                var_export($attribute, true)
            ),
            $position,
            $previous
        );
    }

    /**
     * @return string
     */
    public function typeName()
    {
        return $this->typeName;
    }

    /**
     * @return string
     */
    public function attribute()
    {
        return $this->attribute;
    }

    private $typeName;
    private $attribute;
}
