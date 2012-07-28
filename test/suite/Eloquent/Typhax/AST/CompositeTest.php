<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST;

use Phake;

class CompositeTest extends \PHPUnit_Framework_TestCase
{
    public function testComposite()
    {
        $composite = new Composite('foo');

        $this->assertSame('foo', $composite->separator());

        $composite = new Composite('bar');

        $this->assertSame('bar', $composite->separator());
    }

    public function testTypes()
    {
        $composite = new Composite('foo');

        $this->assertSame(array(), $composite->types());

        $typeBar = new Type('bar');
        $composite->addType($typeBar);

        $this->assertSame(array(
            $typeBar,
        ), $composite->types());

        $typeBaz = new Composite('baz');
        $composite->addType($typeBaz);
        $typeQux = new Type('qux');
        $composite->addType($typeQux);

        $this->assertSame(array(
            $typeBar,
            $typeBaz,
            $typeQux,
        ), $composite->types());
    }

    public function testAccept()
    {
        $composite = new Composite('foo');
        $visitor = Phake::mock(__NAMESPACE__.'\Visitor');
        $composite->accept($visitor);

        Phake::verify($visitor)->visitComposite(
            $this->identicalTo($composite)
        );
    }
}
