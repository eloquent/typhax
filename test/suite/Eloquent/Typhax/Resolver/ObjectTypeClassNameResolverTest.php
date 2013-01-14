<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Resolver;

use Eloquent\Cosmos\ClassName;
use Eloquent\Cosmos\ClassNameResolver;
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
use PHPUnit_Framework_TestCase;

class ObjectTypeClassNameResolverTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classNameResolver = new ClassNameResolver(
            ClassName::fromString('\Foo\Bar\Baz'),
            array(
                array(
                    ClassName::fromString('\Qux\Doom\Splat'),
                    ClassName::fromString('Pip'),
                ),
            )
        );
        $this->_resolver = new ObjectTypeClassNameResolver(
            $this->_classNameResolver
        );
    }

    public function testResolveObjectTypeName()
    {
        $type = new TraversableType(
            new ObjectType(ClassName::fromString('Spam')),
            new OrType(array(
                new ObjectType(ClassName::fromString('Spam')),
                new ObjectType(ClassName::fromString('Pip')),
            )),
            new AndType(array(
                new ObjectType(ClassName::fromString('Spam')),
                new ObjectType(ClassName::fromString('Pip')),
                new TupleType(array(
                    new ObjectType(ClassName::fromString('Spam')),
                    new ObjectType(ClassName::fromString('Pip')),
                )),
            ))
        );
        $expected = new TraversableType(
            new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
            new OrType(array(
                new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
            )),
            new AndType(array(
                new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
                new TupleType(array(
                    new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                    new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
                )),
            ))
        );

        $this->assertEquals($expected, $type->accept($this->_resolver));
    }

    public function testLeaveTypesAlone()
    {
        $type = new TraversableType(
            new ArrayType,
            new OrType(array(
                new BooleanType,
                new CallableType,
                new FloatType,
            )),
            new AndType(array(
                new IntegerType,
                new MixedType,
                new NullType,
                new NumericType,
                new TupleType(array(
                    new ObjectType,
                    new ResourceType,
                    new StreamType,
                    new StringType,
                    new StringableType,
                )),
            ))
        );

        $this->assertEquals($type, $type->accept($this->_resolver));
    }
}
