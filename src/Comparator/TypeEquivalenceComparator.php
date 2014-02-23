<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Comparator;

use Eloquent\Typhax\Type\Type;

class TypeEquivalenceComparator
{
    /**
     * @param Type $left
     * @param Type $right
     *
     * @return integer
     */
    public static function compare(Type $left, Type $right)
    {
        return $right->accept(new TypeEquivalenceComparatorVisitor($left));
    }

    /**
     * @param Type $left
     * @param Type $right
     *
     * @return boolean
     */
    public static function equivalent(Type $left, Type $right)
    {
        return 0 === static::compare($left, $right);
    }
}
