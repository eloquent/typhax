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

use Phake;

class NumericTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $type = new NumericType;
        $visitor = Phake::mock(__NAMESPACE__.'\Visitor');
        $type->accept($visitor);

        Phake::verify($visitor)->visitNumericType(
            $this->identicalTo($type)
        );
    }
}
