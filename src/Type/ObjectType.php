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

class ObjectType implements TraversablePrimaryType
{
    /**
     * @param ClassNameInterface|null $ofType
     */
    public function __construct(ClassNameInterface $ofType = null)
    {
        $this->ofType = $ofType;
    }

    /**
     * @return ClassNameInterface|null
     */
    public function ofType()
    {
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
