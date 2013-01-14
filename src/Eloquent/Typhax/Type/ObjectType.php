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

class ObjectType extends Host implements TraversablePrimaryType
{
    /**
     * @param ClassName|null $ofType
     */
    public function __construct(ClassName $ofType = null)
    {
        $this->ofType = $ofType;
    }

    /**
     * @return ClassName|null
     */
    public function ofType()
    {
        return $this->ofType;
    }

    private $ofType;
}
