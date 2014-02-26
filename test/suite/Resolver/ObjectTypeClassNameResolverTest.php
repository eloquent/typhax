<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Resolver;

use Eloquent\Cosmos\ClassName\ClassName;
use Eloquent\Cosmos\Resolution\ClassNameResolver;
use Eloquent\Cosmos\Resolution\ResolutionContext;
use Eloquent\Cosmos\UseStatement\UseStatement;
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

class ObjectTypeClassNameResolverTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->resolutionContext = new ResolutionContext(
            ClassName::fromString('\Foo\Bar\Baz'),
            array(
                new UseStatement(ClassName::fromString('\Qux\Doom\Splat'), ClassName::fromString('Pip')),
            )
        );
        $this->classNameResolver = new ClassNameResolver;
        $this->resolver = new ObjectTypeClassNameResolver($this->resolutionContext, $this->classNameResolver);
    }

    public function testConstructor()
    {
        $this->assertSame($this->resolutionContext, $this->resolver->resolutionContext());
        $this->assertSame($this->classNameResolver, $this->resolver->classNameResolver());
    }

    public function testConstructorDefaults()
    {
        $this->resolver = new ObjectTypeClassNameResolver($this->resolutionContext);

        $this->assertSame(ClassNameResolver::instance(), $this->resolver->classNameResolver());
    }

    public function testResolveObjectTypeName()
    {
        $type = new TraversableType(
            new ObjectType(ClassName::fromString('Spam')),
            new AndType(array(
                new ObjectType(ClassName::fromString('Spam')),
                new ObjectType(ClassName::fromString('Pip')),
                new TupleType(array(
                    new ObjectType(ClassName::fromString('Spam')),
                    new ObjectType(ClassName::fromString('Pip')),
                )),
            )),
            new OrType(array(
                new ExtensionType(
                    ClassName::fromString('Pip'),
                    array(
                        new ObjectType(ClassName::fromString('Kazaam'))
                    ),
                    array('key' => 'value')
                ),
                new ObjectType(ClassName::fromString('Spam')),
                new ObjectType(ClassName::fromString('Pip')),
            ))
        );
        $expected = new TraversableType(
            new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
            new AndType(array(
                new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
                new TupleType(array(
                    new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                    new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
                )),
            )),
            new OrType(array(
                new ExtensionType(
                    ClassName::fromString('\Qux\Doom\Splat'),
                    array(
                        new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Kazaam'))
                    ),
                    array('key' => 'value')
                ),
                new ObjectType(ClassName::fromString('\Foo\Bar\Baz\Spam')),
                new ObjectType(ClassName::fromString('\Qux\Doom\Splat')),
            ))
        );

        $this->assertEquals($expected, $type->accept($this->resolver));
    }

    public function testLeaveTypesAlone()
    {
        $type = new TraversableType(
            new ArrayType,
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
            )),
            new OrType(array(
                new BooleanType,
                new CallableType,
                new FloatType,
            ))
        );

        $this->assertEquals($type, $type->accept($this->resolver));
    }
}
