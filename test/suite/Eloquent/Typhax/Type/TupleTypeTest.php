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

use Phake;
use PHPUnit_Framework_TestCase;

class TupleTypeTest extends PHPUnit_Framework_TestCase
{
    public function testTypes()
    {
        $types = array(
            Phake::mock(__NAMESPACE__.'\Type'),
            Phake::mock(__NAMESPACE__.'\Type'),
        );
        $type = Phake::partialMock(__NAMESPACE__.'\TupleType', $types);

        $this->assertSame($types, $type->types());
    }
}
