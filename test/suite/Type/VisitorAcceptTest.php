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

/**
 * @covers Eloquent\Typhax\Type\AndType
 * @covers Eloquent\Typhax\Type\ArrayType
 * @covers Eloquent\Typhax\Type\BooleanType
 * @covers Eloquent\Typhax\Type\CallableType
 * @covers Eloquent\Typhax\Type\ExtensionType
 * @covers Eloquent\Typhax\Type\FloatType
 * @covers Eloquent\Typhax\Type\IntegerType
 * @covers Eloquent\Typhax\Type\MixedType
 * @covers Eloquent\Typhax\Type\NullType
 * @covers Eloquent\Typhax\Type\NumericType
 * @covers Eloquent\Typhax\Type\ObjectType
 * @covers Eloquent\Typhax\Type\OrType
 * @covers Eloquent\Typhax\Type\ResourceType
 * @covers Eloquent\Typhax\Type\StreamType
 * @covers Eloquent\Typhax\Type\StringType
 * @covers Eloquent\Typhax\Type\StringableType
 * @covers Eloquent\Typhax\Type\TraversableType
 * @covers Eloquent\Typhax\Type\TupleType
 */
class VisitorAcceptTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->visitor = Phake::mock(__NAMESPACE__ . '\Visitor');
    }

    /**
     * @dataProvider typeNames
     */
    public function testAccept($className)
    {
        $type = Phake::mock(__NAMESPACE__ . '\\' . $className);

        Phake::when($type)
            ->accept(Phake::anyParameters())
            ->thenCallParent();

        Phake::when($this->visitor)
            ->{'visit' . $className}($type)
            ->thenReturn('<visitor result>');

        $result = $type->accept($this->visitor);
        $this->assertSame('<visitor result>', $result);
    }

    public function typeNames()
    {
        return array(
            array('AndType'),
            array('ArrayType'),
            array('BooleanType'),
            array('CallableType'),
            array('ExtensionType'),
            array('FloatType'),
            array('IntegerType'),
            array('MixedType'),
            array('NullType'),
            array('NumericType'),
            array('ObjectType'),
            array('OrType'),
            array('ResourceType'),
            array('StreamType'),
            array('StringType'),
            array('StringableType'),
            array('TraversableType'),
            array('TupleType'),
        );
    }
}
