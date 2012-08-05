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
use PHPUnit_Framework_TestCase;

class ObjectTypeTest extends PHPUnit_Framework_TestCase
{
    public function testOfType()
    {
        $type = new ObjectType('foo');

        $this->assertSame('foo', $type->ofType());
    }

    public function testAccept()
    {
        $type = new ObjectType;
        $visitor = Phake::mock(__NAMESPACE__.'\Visitor');
        Phake::when($visitor)
            ->visitObjectType(Phake::anyParameters())
            ->thenReturn('foo')
        ;

        $this->assertSame('foo', $type->accept($visitor));
        Phake::verify($visitor)->visitObjectType(
            $this->identicalTo($type)
        );
    }
}
