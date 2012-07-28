<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST;

class Type implements Node
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return array<string,mixed>
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @param Node $type
     */
    public function addSubType(Node $type)
    {
        $this->subTypes[] = $type;
    }

    /**
     * @return array<integer,Node>
     */
    public function subTypes()
    {
        return $this->subTypes;
    }

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitType($this);
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array<string,mixed>
     */
    protected $attributes = array();

    /**
     * @var array<integer,Node>
     */
    protected $subTypes = array();
}
