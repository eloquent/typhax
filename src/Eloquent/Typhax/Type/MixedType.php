<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

class MixedType implements TraversablePrimaryType
{
    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitMixedType($this);
    }
}
