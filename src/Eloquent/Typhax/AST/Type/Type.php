<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST\Type;

use Eloquent\Typhax\AST\Node;
use Eloquent\Typhax\AST\Visitor;

abstract class Type implements Node
{
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

    private $attributes = array();
    private $subTypes = array();
}
