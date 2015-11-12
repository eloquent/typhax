<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

/**
 * Represents a traversable type.
 *
 * @api
 */
class TraversableType implements Type
{
    /**
     * Construct a new traversable type.
     *
     * @param TraversablePrimaryType $primaryType The primary type.
     * @param Type|null              $keyType     The key type, or null if the key type was omitted.
     * @param Type                   $valueType   The value type.
     */
    public function __construct(
        TraversablePrimaryType $primaryType,
        Type $keyType = null,
        Type $valueType
    ) {
        $this->primaryType = $primaryType;
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    /**
     * Get the primary type.
     *
     * @api
     *
     * @return TraversablePrimaryType The primary type.
     */
    public function primaryType()
    {
        return $this->primaryType;
    }

    /**
     * Get the key type.
     *
     * @api
     *
     * @return Type|null The key type.
     */
    public function keyType()
    {
        return $this->keyType;
    }

    /**
     * Get the value type.
     *
     * @api
     *
     * @return Type The value type.
     */
    public function valueType()
    {
        return $this->valueType;
    }

    /**
     * Accept a visitor.
     *
     * @api
     *
     * @param TypeVisitor $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(TypeVisitor $visitor)
    {
        return $visitor->visitTraversableType($this);
    }

    private $primaryType;
    private $keyType;
    private $valueType;
}
