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

use Eloquent\Cosmos\ClassName\ClassName;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\ExtensionType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use PHPUnit_Framework_TestCase;

/**
 * @covers Eloquent\Typhax\Comparator\TypeEquivalenceComparator
 * @covers Eloquent\Typhax\Comparator\TypeEquivalenceComparatorVisitor
 */
class TypeEquivalenceComparatorTest extends PHPUnit_Framework_TestCase
{
    public function compareData()
    {
        return array(
            'Single type equivalence' => array(
                new StringType,
                new StringType,
                0,
            ),
            'Single type non-equivalence' => array(
                new StringType,
                new StringableType,
                -1,
            ),
            'Out-of order with duplicates AND equivalence' => array(
                new AndType(array(new NullType, new StringType)),
                new AndType(array(new StringType, new NullType, new StringType, new StringType)),
                0,
            ),
            'Out-of order AND non-equivalence 1' => array(
                new AndType(array(new NullType, new StringType)),
                new AndType(array(new StringType, new NullType, new ObjectType)),
                -1,
            ),
            'Out-of order AND non-equivalence 2' => array(
                new AndType(array(new ArrayType)),
                new AndType(array(new ArrayType, new NullType)),
                -1,
            ),
            'Out-of order AND non-equivalence 3' => array(
                new AndType(array(new ArrayType, new NullType)),
                new AndType(array(new ArrayType)),
                1,
            ),
            'Out-of order with duplicates OR equivalence' => array(
                new OrType(
                    array(
                        new ArrayType,
                        new BooleanType,
                        new CallableType,
                        new FloatType,
                        new IntegerType,
                        new MixedType,
                        new NullType,
                        new NumericType,
                        new ObjectType,
                        new ResourceType,
                        new StreamType,
                        new StringType,
                        new StringableType,
                        new ArrayType,
                        new ArrayType,
                    )
                ),
                new OrType(
                    array(
                        new BooleanType,
                        new IntegerType,
                        new FloatType,
                        new MixedType,
                        new CallableType,
                        new StringableType,
                        new NullType,
                        new NumericType,
                        new ObjectType,
                        new StreamType,
                        new ArrayType,
                        new StringType,
                        new ResourceType,
                    )
                ),
                0
            ),
            'Traversable equivalence' => array(
                new TraversableType(new ArrayType, new FloatType, new StringType),
                new TraversableType(new ArrayType, new FloatType, new StringType),
                0,
            ),
            'Traversable primary non equivalence' => array(
                new TraversableType(new ArrayType, new FloatType, new StringType),
                new TraversableType(new MixedType, new FloatType, new StringType),
                -1,
            ),
            'Traversable key non equivalence' => array(
                new TraversableType(new ArrayType, new FloatType, new StringType),
                new TraversableType(new ArrayType, new FloatType, new IntegerType),
                1,
            ),
            'Traversable value non equivalence' => array(
                new TraversableType(new ArrayType, new FloatType, new StringType),
                new TraversableType(new ArrayType, new BooleanType, new StringType),
                1,
            ),
            'Traversable and non-traversable non equivalence' => array(
                new StringType,
                new TraversableType(new ArrayType, new FloatType, new StringType),
                -1,
            ),
            'Tuple equivalence' => array(
                new TupleType(array(new ArrayType, new StringType, new FloatType)),
                new TupleType(array(new ArrayType, new StringType, new FloatType)),
                0,
            ),
            'Tuple non equivalence' => array(
                new TupleType(array(new ArrayType, new StringType, new FloatType)),
                new TupleType(array(new ArrayType, new FloatType, new StringType)),
                1,
            ),
            'Tuple and non-tuple non equivalence' => array(
                new StringType,
                new TupleType(array(new ArrayType, new FloatType, new StringType)),
                -1,
            ),
            'Composite and non-composite non equivalence' => array(
                new StringType,
                new OrType(array(new ArrayType, new FloatType, new StringType)),
                1,
            ),
            'Stream type readable non-equivalence' => array(
                new StreamType(true),
                new StreamType(false),
                1,
            ),
            'Stream type writable non-equivalence' => array(
                new StreamType(null, true),
                new StreamType(null, false),
                1,
            ),
            'Attribute null and non-null non-equivalence 1' => array(
                new StreamType(true),
                new StreamType,
                1,
            ),
            'Attribute null and non-null non-equivalence 2' => array(
                new StreamType,
                new StreamType(true),
                -1,
            ),
            'Extension type equivalence' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'bar')),
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'bar')),
                0,
            ),
            'Extension type non-equivalence (different type)' => array(
                new StreamType,
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'bar')),
                1,
            ),
            'Extension type non-equivalence (different class name)' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'bar')),
                new ExtensionType(
                    ClassName::fromString('This\is\Not\Equivalent'),
                    array(new IntegerType),
                    array('foo' => 'bar')
                ),
                -1,
            ),
            'Extension type non-equivalence (different sub-types)' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'bar')),
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new StringType), array('foo' => 'bar')),
                -1,
            ),
            'Extension type non-equivalence (< attribute values)' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'A')),
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'B')),
                -1,
            ),
            'Extension type non-equivalence (> attribute values)' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'B')),
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('foo' => 'A')),
                1,
            ),
            'Extension type non-equivalence (< attribute keys)' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('A' => 'bar')),
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('B' => 'bar')),
                -1,
            ),
            'Extension type non-equivalence (> attribute keys)' => array(
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('B' => 'bar')),
                new ExtensionType(ClassName::fromString('Foo\Bar'), array(new IntegerType), array('A' => 'bar')),
                1,
            ),
        );

        return $data;
    }

    /**
     * @dataProvider compareData
     */
    public function testCompareAndEquivalent($left, $right, $expected)
    {
        if ($expected > 0) {
            $this->assertGreaterThan(0, TypeEquivalenceComparator::compare($left, $right));
        } elseif ($expected < 0) {
            $this->assertLessThan(0, TypeEquivalenceComparator::compare($left, $right));
        } else {
            $this->assertSame(0, TypeEquivalenceComparator::compare($left, $right));
        }
        $this->assertSame(0 === $expected, TypeEquivalenceComparator::equivalent($left, $right));
    }

    public function testSort()
    {
        $types = array(
            new StringType,
            new TraversableType(new ArrayType, new OrType(array(new IntegerType, new FloatType)), new StringType),
            new BooleanType,
            new TraversableType(new ArrayType, null, new StringType),
            new TraversableType(new ArrayType),
            new NullType,
        );
        usort($types, __NAMESPACE__.'\TypeEquivalenceComparator::compare');
        $expected = array(
            new BooleanType,
            new NullType,
            new StringType,
            new TraversableType(new ArrayType),
            new TraversableType(new ArrayType, null, new StringType),
            new TraversableType(new ArrayType, new OrType(array(new IntegerType, new FloatType)), new StringType),
        );

        $this->assertEquals($expected, $types);
    }
}
