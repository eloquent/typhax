<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST\Type;

use Eloquent\Typhax\AST\Composite;
use Phake;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testAttributes()
    {
        $type = Phake::partialMock(__NAMESPACE__.'\Type');

        $this->assertSame(array(), $type->attributes());

        $type->setAttribute('bar', 'baz');

        $this->assertSame(array(
            'bar' => 'baz',
        ), $type->attributes());

        $type->setAttribute('qux', 'doom');
        $type->setAttribute('splat', 'buzz');

        $this->assertSame(array(
            'bar' => 'baz',
            'qux' => 'doom',
            'splat' => 'buzz',
        ), $type->attributes());
    }

    public function testSubTypes()
    {
        $type = Phake::partialMock(__NAMESPACE__.'\Type');

        $this->assertSame(array(), $type->subTypes());

        $subTypeBar = Phake::partialMock(__NAMESPACE__.'\Type');
        $type->addSubType($subTypeBar);

        $this->assertSame(array(
            $subTypeBar,
        ), $type->subTypes());

        $subTypeBaz = new Composite('baz');
        $type->addSubType($subTypeBaz);
        $subTypeQux = Phake::partialMock(__NAMESPACE__.'\Type');
        $type->addSubType($subTypeQux);

        $this->assertSame(array(
            $subTypeBar,
            $subTypeBaz,
            $subTypeQux,
        ), $type->subTypes());
    }
}
