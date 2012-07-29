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

class ObjectType implements TraversablePrimaryType
{
    /**
     * @param string|null $ofType
     */
    public function __construct($ofType = null)
    {
        $this->ofType = $ofType;
    }

    /**
     * @return string|null
     */
    public function ofType() {
        return $this->ofType;
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

    private $ofType;
}
