<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Eloquent\Cosmos\ClassName;

class ExtensionType implements Type
{
    /**
     * @param ClassName   $className
     * @param array<Type> $types
     * @param array       $attributes
     */
    public function __construct(ClassName $className, array $types, array $attributes)
    {
        $this->className = $className;
        $this->types = $types;
        $this->attributes = $attributes;
    }

    /**
     * @return ClassName
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return array<Type>
     */
    public function types()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitExtensionType($this);
    }

    private $className;
    private $types;
    private $attributes;
}
