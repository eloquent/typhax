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
 * Represents a tuple type.
 *
 * @api
 */
class TupleType implements Type
{
    /**
     * Construct a tuple type.
     *
     * @param array<Type> $types The sub-types.
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * Get the sub-types.
     *
     * @api
     *
     * @return array<Type> The sub-types.
     */
    public function types()
    {
        return $this->types;
    }

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
        return $visitor->visitTupleType($this);
    }

    private $types;
}
