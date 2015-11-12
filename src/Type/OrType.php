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
 * Represents an or type.
 *
 * @api
 */
class OrType implements Type
{
    /**
     * Construct a new or type.
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
     * @return array<Type> The sub-types.
     */
    public function types()
    {
        return $this->types;
    }

    /**
     * Accept a visitor.
     *
     * @param TypeVisitor $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(TypeVisitor $visitor)
    {
        return $visitor->visitOrType($this);
    }

    private $types;
}
