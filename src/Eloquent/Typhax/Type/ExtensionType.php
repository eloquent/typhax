<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Eloquent\Cosmos\ClassName;
use Icecave\Visita\Host;

class ExtensionType extends Host implements Type
{
    /**
     * @param Type $baseType
     * @param ClassName $className
     * @param array $attributes
     */
    public function __construct(Type $baseType, ClassName $className, array $attributes)
    {
        $this->baseType = $baseType;
        $this->className = $className;
        $this->attributes = $attributes;
    }

    /**
     * @return Type
     */
    public function baseType()
    {
        return $this->baseType;
    }

    /**
     * @return ClassName
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    private $baseType;
    private $className;
    private $attributes;
}
