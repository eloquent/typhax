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

use Icecave\Visita\Host;

class TupleType extends Host implements Type
{
    /**
     * @param array<Type> $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array<Type>
     */
    public function types()
    {
        return $this->types;
    }

    private $types;
}
