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

class ArrayType extends Type
{
    const TYPE_NAME = 'array';

    /**
     * @return string
     */
    public function name() {
        return static::TYPE_NAME;
    }

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitArrayType($this);
    }
}