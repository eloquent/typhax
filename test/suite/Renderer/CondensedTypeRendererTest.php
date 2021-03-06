<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Renderer;

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
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Typhax\Renderer\CondensedTypeRenderer
 */
class CondensedTypeRendererTest extends PHPUnit_Framework_TestCase
{
    public function renderData()
    {
        $data = array();

        $type = new OrType(array(
            new ArrayType(),
            new BooleanType(),
            new CallableType(),
            new FloatType(),
            new IntegerType(),
            new MixedType(),
            new NullType(),
            new NumericType(),
            new ObjectType(),
            new ResourceType(),
            new StreamType(),
            new StringType(),
            new StringableType(),
        ));
        $expected = 'array|boolean|callable|float|integer|mixed|null|numeric|object|resource|stream|string|stringable';
        $data['Basic types in an OR'] = array($expected, $type);

        $type = new AndType(array(
            new ObjectType('Foo'),
            new ObjectType('Bar'),
            new ObjectType('Baz'),
        ));
        $expected = 'Foo+Bar+Baz';
        $data['Objects ofType in an AND'] = array($expected, $type);

        $type = new TraversableType(
            new ArrayType(),
            new IntegerType(),
            new StringType()
        );
        $expected = 'array<integer,string>';
        $data['Traversable type'] = array($expected, $type);

        $type = new TraversableType(
            new ArrayType(),
            null,
            new StringType()
        );
        $expected = 'array<string>';
        $data['Traversable type with omitted key'] = array($expected, $type);

        $type = new TraversableType(
            new ArrayType(),
            null,
            new MixedType()
        );
        $expected = 'array';
        $data['Array type with omitted key and mixed value'] = array($expected, $type);

        $type = new TraversableType(
            new ObjectType('Foo'),
            null,
            new MixedType()
        );
        $expected = 'Foo<mixed>';
        $data['Traversable object type with omitted key and mixed value'] = array($expected, $type);

        $type = new TraversableType(
            new MixedType(),
            null,
            new MixedType()
        );
        $expected = 'mixed<mixed>';
        $data['Traversable mixed type with omitted key and mixed value'] = array($expected, $type);

        $type = new TraversableType(
            new ArrayType(),
            new MixedType(),
            new StringType()
        );
        $expected = 'array<mixed,string>';
        $data['Traversable type with mixed key'] = array($expected, $type);

        $type = new TraversableType(
            new ArrayType(),
            new MixedType(),
            new MixedType()
        );
        $expected = 'array<mixed,mixed>';
        $data['Array type with mixed key and mixed value'] = array($expected, $type);

        $type = new TraversableType(
            new ObjectType('Foo'),
            new MixedType(),
            new MixedType()
        );
        $expected = 'Foo<mixed,mixed>';
        $data['Traversable object type with mixed key and value'] = array($expected, $type);

        $type = new TraversableType(
            new MixedType(),
            new MixedType(),
            new MixedType()
        );
        $expected = 'mixed<mixed,mixed>';
        $data['Traversable mixed type with mixed key and value'] = array($expected, $type);

        $type = new TupleType(array(
            new BooleanType(),
            new CallableType(),
            new FloatType(),
        ));
        $expected = 'tuple<boolean,callable,float>';
        $data['Tuple type'] = array($expected, $type);

        $type = new ResourceType('foo');
        $expected = "resource{ofType:'foo'}";
        $data['Resource ofType'] = array($expected, $type);

        $type = new StreamType(true);
        $expected = 'stream{readable:true}';
        $data['Readable stream'] = array($expected, $type);

        $type = new StreamType(false);
        $expected = 'stream{readable:false}';
        $data['Non-readable stream'] = array($expected, $type);

        $type = new StreamType(null, true);
        $expected = 'stream{writable:true}';
        $data['Writable stream'] = array($expected, $type);

        $type = new StreamType(null, false);
        $expected = 'stream{writable:false}';
        $data['Non-writable stream'] = array($expected, $type);

        $type = new StreamType(true, true);
        $expected = 'stream{readable:true,writable:true}';
        $data['Read-write stream'] = array($expected, $type);

        $type = new ObjectType('\foo');
        $expected = '\foo';
        $data['Absolute class name'] = array($expected, $type);

        $type = new ExtensionType(
            '\ext',
            array(),
            array()
        );
        $expected = ':\ext';
        $data['Extension type'] = array($expected, $type);

        $type = new ExtensionType(
            '\ext',
            array(new IntegerType(), new ArrayType()),
            array()
        );
        $expected = ':\ext<integer,array>';
        $data['Extension type with types'] = array($expected, $type);

        $type = new ExtensionType(
            '\ext',
            array(),
            array('foo' => 'bar', 'quux' => 'doom')
        );
        $expected = ":\\ext{foo:'bar',quux:'doom'}";
        $data['Extension type with attributes'] = array($expected, $type);

        $type = new ExtensionType(
            '\ext',
            array(new IntegerType(), new ArrayType()),
            array('foo' => 'bar', 'quux' => 'doom')
        );
        $expected = ":\\ext<integer,array>{foo:'bar',quux:'doom'}";
        $data['Extension type with types and attributes'] = array($expected, $type);

        return $data;
    }

    /**
     * @dataProvider renderData
     */
    public function testRender($expected, Type $type)
    {
        $renderer = CondensedTypeRenderer::create();

        $this->assertSame($expected, $renderer->render($type));
    }
}
