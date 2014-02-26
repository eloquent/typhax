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

/**
 * Represents a traversable type.
 */
class TraversableType implements TraversableTypeInterface
{
    /**
     * Construct a new traversable type.
     *
     * @param TraversablePrimaryTypeInterface|null $primaryType The primary type.
     * @param TypeInterface|null                   $keyType     The key type.
     * @param TypeInterface|null                   $valueType   The value type.
     */
    public function __construct(
        TraversablePrimaryTypeInterface $primaryType = null,
        TypeInterface $valueType = null,
        TypeInterface $keyType = null
    ) {
        if (null === $primaryType) {
            $primaryType = new MixedType;
        }
        if (null === $valueType) {
            $valueType = new MixedType;
        }
        if (null === $keyType) {
            $keyType = new MixedType;
        }

        $this->primaryType = $primaryType;
        $this->valueType = $valueType;
        $this->keyType = $keyType;
    }

    /**
     * Get the primary type.
     *
     * @return TraversablePrimaryTypeInterface The primary type.
     */
    public function primaryType()
    {
        return $this->primaryType;
    }

    /**
     * Get the value type.
     *
     * @return TypeInterface The value type.
     */
    public function valueType()
    {
        return $this->valueType;
    }

    /**
     * Get the key type.
     *
     * @return TypeInterface The key type.
     */
    public function keyType()
    {
        return $this->keyType;
    }

    /**
     * Get the sub-types.
     *
     * @return array<integer,TypeInterface> The sub-types.
     */
    public function types()
    {
        return array($this->keyType(), $this->valueType());
    }

    /**
     * Accept a visitor.
     *
     * @param VisitorInterface $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visitTraversableType($this);
    }

    private $primaryType;
    private $valueType;
    private $keyType;
}
