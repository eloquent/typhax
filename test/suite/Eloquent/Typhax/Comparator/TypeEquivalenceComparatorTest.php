<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Comparator;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
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
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhax\Type\Visitor;
use PHPUnit_Framework_TestCase;

class TypeEquivalenceComparatorTest extends PHPUnit_Framework_TestCase
{
    public function compareData()
    {
        $data = array();

        $left = new StringType;
        $right = new StringType;
        $expected = 0;
        $data['Single type equivalence'] = array($expected, $left, $right);

        $left = new StringType;
        $right = new StringableType;
        $expected = -1;
        $data['Single type non-equivalence'] = array($expected, $left, $right);

        $left = new AndType(array(
            new NullType,
            new StringType,
        ));
        $right = new AndType(array(
            new StringType,
            new NullType,
            new StringType,
            new StringType,
        ));
        $expected = 0;
        $data['Out-of order with duplicates AND equivalence'] = array($expected, $left, $right);

        $left = new AndType(array(
            new NullType,
            new StringType,
        ));
        $right = new AndType(array(
            new StringType,
            new NullType,
            new ObjectType,
        ));
        $expected = -1;
        $data['Out-of order AND non-equivalence 1'] = array($expected, $left, $right);

        $left = new AndType(array(
            new ArrayType,
        ));
        $right = new AndType(array(
            new ArrayType,
            new NullType,
        ));
        $expected = -1;
        $data['Out-of order AND non-equivalence 2'] = array($expected, $left, $right);

        $left = new AndType(array(
            new ArrayType,
            new NullType,
        ));
        $right = new AndType(array(
            new ArrayType,
        ));
        $expected = 1;
        $data['Out-of order AND non-equivalence 3'] = array($expected, $left, $right);

        $left = new OrType(array(
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
        ));
        $right = new OrType(array(
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
        ));
        $expected = 0;
        $data['Out-of order with duplicates OR equivalence'] = array($expected, $left, $right);

        $left = new TraversableType(
            new ArrayType,
            new StringType,
            new FloatType
        );
        $right = new TraversableType(
            new ArrayType,
            new StringType,
            new FloatType
        );
        $expected = 0;
        $data['Traversable equivalence'] = array($expected, $left, $right);

        $left = new TraversableType(
            new ArrayType,
            new StringType,
            new FloatType
        );
        $right = new TraversableType(
            new MixedType,
            new StringType,
            new FloatType
        );
        $expected = -1;
        $data['Traversable primary non equivalence'] = array($expected, $left, $right);

        $left = new TraversableType(
            new ArrayType,
            new StringType,
            new FloatType
        );
        $right = new TraversableType(
            new ArrayType,
            new IntegerType,
            new FloatType
        );
        $expected = 1;
        $data['Traversable key non equivalence'] = array($expected, $left, $right);

        $left = new TraversableType(
            new ArrayType,
            new StringType,
            new FloatType
        );
        $right = new TraversableType(
            new ArrayType,
            new StringType,
            new BooleanType
        );
        $expected = 1;
        $data['Traversable value non equivalence'] = array($expected, $left, $right);

        $left = new StringType;
        $right = new TraversableType(
            new ArrayType,
            new StringType,
            new FloatType
        );
        $expected = -1;
        $data['Traversable and non-traversable non equivalence'] = array($expected, $left, $right);

        $left = new TupleType(array(
            new ArrayType,
            new StringType,
            new FloatType,
        ));
        $right = new TupleType(array(
            new ArrayType,
            new StringType,
            new FloatType,
        ));
        $expected = 0;
        $data['Tuple equivalence'] = array($expected, $left, $right);

        $left = new TupleType(array(
            new ArrayType,
            new StringType,
            new FloatType,
        ));
        $right = new TupleType(array(
            new ArrayType,
            new FloatType,
            new StringType,
        ));
        $expected = 1;
        $data['Tuple non equivalence'] = array($expected, $left, $right);

        $left = new StringType;
        $right = new TupleType(array(
            new ArrayType,
            new FloatType,
            new StringType,
        ));
        $expected = -1;
        $data['Tuple and non-tuple non equivalence'] = array($expected, $left, $right);

        $left = new StringType;
        $right = new OrType(array(
            new ArrayType,
            new FloatType,
            new StringType,
        ));
        $expected = 1;
        $data['Composite and non-composite non equivalence'] = array($expected, $left, $right);

        $left = new StreamType(true);
        $right = new StreamType(false);
        $expected = 1;
        $data['Stream type readable non-equivalence'] = array($expected, $left, $right);

        $left = new StreamType(null, true);
        $right = new StreamType(null, false);
        $expected = 1;
        $data['Stream type writable non-equivalence'] = array($expected, $left, $right);

        $left = new StreamType(true);
        $right = new StreamType;
        $expected = 1;
        $data['Attribute null and non-null non-equivalence 1'] = array($expected, $left, $right);

        $left = new StreamType;
        $right = new StreamType(true);
        $expected = -1;
        $data['Attribute null and non-null non-equivalence 2'] = array($expected, $left, $right);

        return $data;
    }

    /**
     * @dataProvider compareData
     */
    public function testCompareAndEquivalent($expected, Type $left, Type $right)
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
            new TraversableType(
                new ArrayType,
                new StringType,
                new OrType(array(
                    new IntegerType,
                    new FloatType,
                ))
            ),
            new BooleanType,
            new TraversableType(
                new ArrayType,
                new StringType,
                new MixedType
            ),
            new TraversableType(
                new ArrayType,
                new MixedType,
                new MixedType
            ),
            new NullType,
        );
        usort($types, __NAMESPACE__.'\TypeEquivalenceComparator::compare');
        $expected = array(
            new BooleanType,
            new NullType,
            new StringType,
            new TraversableType(
                new ArrayType,
                new MixedType,
                new MixedType
            ),
            new TraversableType(
                new ArrayType,
                new StringType,
                new MixedType
            ),
            new TraversableType(
                new ArrayType,
                new StringType,
                new OrType(array(
                    new IntegerType,
                    new FloatType,
                ))
            ),
        );

        $this->assertEquals($expected, $types);
    }
}
