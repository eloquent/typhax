<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Comparator;

use Eloquent\Typhax\Type\Type;

/**
 * Compares types for equivalence.
 *
 * @api
 */
class TypeEquivalenceComparator
{
    /**
     * Create a new type equivalence comparator.
     *
     * @api
     *
     * @return self The comparator.
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Returns true if the supplied types are equivalent.
     *
     * @api
     *
     * @param Type $left  The left-hand type.
     * @param Type $right The right-hand type.
     *
     * @return boolean True if the types are equivalent.
     */
    public function isEquivalent(Type $left, Type $right)
    {
        return 0 === $this->compare($left, $right);
    }

    /**
     * Compare the supplied types for equivalence.
     *
     * @param Type $left  The left-hand type.
     * @param Type $right The right-hand type.
     *
     * @return integer The comparison result.
     */
    public function compare(Type $left, Type $right)
    {
        return
            $right->accept(new TypeEquivalenceComparatorVisitor($this, $left));
    }
}
