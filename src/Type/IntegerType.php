<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

/**
 * Represents an integer type.
 *
 * @api
 */
class IntegerType implements Type
{
    /**
     * Accept a visitor.
     *
     * @api
     *
     * @param TypeVisitor $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(TypeVisitor $visitor)
    {
        return $visitor->visitIntegerType($this);
    }
}
