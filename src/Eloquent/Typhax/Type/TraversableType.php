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

class TraversableType extends Host implements Type
{
    /**
     * @param TraversablePrimaryType $primaryType
     * @param Type $keyType
     * @param Type $valueType
     */
    public function __construct(
        TraversablePrimaryType $primaryType,
        Type $keyType,
        Type $valueType
    ) {
        $this->primaryType = $primaryType;
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    /**
     * @return TraversablePrimaryType
     */
    public function primaryType()
    {
        return $this->primaryType;
    }

    /**
     * @return Type
     */
    public function keyType()
    {
        return $this->keyType;
    }

    /**
     * @return Type
     */
    public function valueType()
    {
        return $this->valueType;
    }

    private $primaryType;
    private $keyType;
    private $valueType;
}
