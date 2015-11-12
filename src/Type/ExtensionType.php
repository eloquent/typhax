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
 * Represents an extension type.
 *
 * @api
 */
class ExtensionType implements Type
{
    /**
     * Construct a new extension type.
     *
     * @param string      $className  The class name.
     * @param array<Type> $types      The sub-types.
     * @param array       $attributes The attributes.
     */
    public function __construct($className, array $types, array $attributes)
    {
        $this->className = $className;
        $this->types = $types;
        $this->attributes = $attributes;
    }

    /**
     * Get the class name.
     *
     * @api
     *
     * @return string The class name.
     */
    public function className()
    {
        return $this->className;
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
     * Get the attributes.
     *
     * @api
     *
     * @return array The attributes.
     */
    public function attributes()
    {
        return $this->attributes;
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
        return $visitor->visitExtensionType($this);
    }

    private $className;
    private $types;
    private $attributes;
}
