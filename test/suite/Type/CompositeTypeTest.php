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

use Phake;
use PHPUnit_Framework_TestCase;

class CompositeTypeTest extends PHPUnit_Framework_TestCase
{
    public function testTypes()
    {
        $types = array(
            Phake::mock(__NAMESPACE__.'\TypeInterface'),
            Phake::mock(__NAMESPACE__.'\TypeInterface'),
        );
        $type = Phake::partialMock(__NAMESPACE__.'\AbstractCompositeType', $types);

        $this->assertSame($types, $type->types());
    }
}
