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
 * An abstract base class for implementing composite types.
 */
abstract class AbstractCompositeType implements CompositeTypeInterface
{
    /**
     * Construct a new composite type.
     *
     * @param array<integer,TypeInterface>|null $types The sub-types.
     */
    public function __construct(array $types = null)
    {
        if (null === $types) {
            $types = array();
        }

        $this->types = $types;
    }

    /**
     * Get the sub-types.
     *
     * @return array<integer,TypeInterface> The sub-types.
     */
    public function types()
    {
        return $this->types;
    }

    private $types;
}
