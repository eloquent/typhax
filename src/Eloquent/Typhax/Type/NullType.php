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

class NullType implements Type
{
    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitNullType($this);
    }
}
