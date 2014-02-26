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

use Eloquent\Typhax\Type\TypeInterface;

class TypeEquivalenceComparator
{
    /**
     * @param TypeInterface $left
     * @param TypeInterface $right
     *
     * @return integer
     */
    public static function compare(TypeInterface $left, TypeInterface $right)
    {
        return $right->accept(new TypeEquivalenceComparatorVisitor($left));
    }

    /**
     * @param TypeInterface $left
     * @param TypeInterface $right
     *
     * @return boolean
     */
    public static function equivalent(TypeInterface $left, TypeInterface $right)
    {
        return 0 === static::compare($left, $right);
    }
}
