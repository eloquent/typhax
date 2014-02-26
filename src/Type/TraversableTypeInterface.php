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
 * The interface implemented by traversable types.
 */
interface TraversableTypeInterface extends CompositeTypeInterface
{
    /**
     * Get the primary type.
     *
     * @return TraversablePrimaryTypeInterface The primary type.
     */
    public function primaryType();

    /**
     * Get the value type.
     *
     * @return TypeInterface The value type.
     */
    public function valueType();

    /**
     * Get the key type.
     *
     * @return TypeInterface The key type.
     */
    public function keyType();
}
