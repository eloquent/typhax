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
 * Represents a resource type.
 *
 * @api
 */
class ResourceType implements Type
{
    /**
     * Construct a new resource type.
     *
     * @param string|null $ofType The of type.
     */
    public function __construct($ofType = null)
    {
        $this->ofType = $ofType;
    }

    /**
     * Get the of type.
     *
     * @api
     *
     * @return string|null The of type.
     */
    public function ofType()
    {
        return $this->ofType;
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
        return $visitor->visitResourceType($this);
    }

    private $ofType;
}
