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

use Eloquent\Typhax\AST\Visitor;

class ObjectType extends Type
{
    const TYPE_NAME = 'object';

    /**
     * @param string|null $instanceOf
     */
    public function __construct($instanceOf = null)
    {
        $this->instanceOf = $instanceOf;
    }

    /**
     * @return string
     */
    public function name() {
        if (null === $this->instanceOf) {
            return static::TYPE_NAME;
        }

        return $this->instanceOf;
    }

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitObjectType($this);
    }

    private $instanceOf;
}