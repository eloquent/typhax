<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Eloquent\Cosmos\ClassName\ClassNameInterface;

/**
 * Represents an object type.
 */
class ObjectType implements TraversablePrimaryTypeInterface
{
    /**
     * Construct a new object type.
     *
     * @param ClassNameInterface|null $ofType The class name, or null if the object can be any type.
     */
    public function __construct(ClassNameInterface $ofType = null)
    {
        $this->ofType = $ofType;
    }

    /**
     * Get the class name.
     *
     * @return ClassNameInterface|null The class name, or null if the object can be any type.
     */
    public function ofType()
    {
        return $this->ofType;
    }

    /**
     * Accept a visitor.
     *
     * @param VisitorInterface $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visitObjectType($this);
    }

    private $ofType;
}
