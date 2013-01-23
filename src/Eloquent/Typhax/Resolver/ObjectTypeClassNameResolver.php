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

use Eloquent\Cosmos\ClassNameResolver;
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
use Eloquent\Typhax\Type\Visitor;

class ObjectTypeClassNameResolver implements Visitor
{
    /**
     * @param ClassNameResolver $classNameResolver
     */
    public function __construct(ClassNameResolver $classNameResolver)
    {
        $this->classNameResolver = $classNameResolver;
    }

    /**
     * @return ClassNameResolver
     */
    public function classNameResolver()
    {
        return $this->classNameResolver;
    }

    /**
     * @param AndType $type
     *
     * @return Type
     */
    public function visitAndType(AndType $type)
    {
        $types = array();
        foreach ($type->types() as $subType) {
            $types[] = $subType->accept($this);
        }

        return new AndType($types);
    }

    /**
     * @param ArrayType $type
     *
     * @return Type
     */
    public function visitArrayType(ArrayType $type)
    {
        return $type;
    }

    /**
     * @param BooleanType $type
     *
     * @return Type
     */
    public function visitBooleanType(BooleanType $type)
    {
        return $type;
    }

    /**
     * @param CallableType $type
     *
     * @return Type
     */
    public function visitCallableType(CallableType $type)
    {
        return $type;
    }

    /**
     * @param ExtensionType $type
     *
     * @return mixed
     */
    public function visitExtensionType(ExtensionType $type)
    {
        $types = array();
        foreach ($type->types() as $subType) {
            $types[] = $subType->accept($this);
        }

        return new ExtensionType(
            $this->classNameResolver()->resolve(
                $type->className()
            ),
            $types,
            $type->attributes()
        );
    }

    /**
     * @param FloatType $type
     *
     * @return Type
     */
    public function visitFloatType(FloatType $type)
    {
        return $type;
    }

    /**
     * @param IntegerType $type
     *
     * @return Type
     */
    public function visitIntegerType(IntegerType $type)
    {
        return $type;
    }

    /**
     * @param MixedType $type
     *
     * @return Type
     */
    public function visitMixedType(MixedType $type)
    {
        return $type;
    }

    /**
     * @param NullType $type
     *
     * @return Type
     */
    public function visitNullType(NullType $type)
    {
        return $type;
    }

    /**
     * @param NumericType $type
     *
     * @return Type
     */
    public function visitNumericType(NumericType $type)
    {
        return $type;
    }

    /**
     * @param ObjectType $type
     *
     * @return Type
     */
    public function visitObjectType(ObjectType $type)
    {
        if (null === $type->ofType()) {
            return $type;
        }

        return new ObjectType(
            $this->classNameResolver()->resolve(
                $type->ofType()
            )
        );
    }

    /**
     * @param OrType $type
     *
     * @return Type
     */
    public function visitOrType(OrType $type)
    {
        $types = array();
        foreach ($type->types() as $subType) {
            $types[] = $subType->accept($this);
        }

        return new OrType($types);
    }

    /**
     * @param ResourceType $type
     *
     * @return Type
     */
    public function visitResourceType(ResourceType $type)
    {
        return $type;
    }

    /**
     * @param StreamType $type
     *
     * @return Type
     */
    public function visitStreamType(StreamType $type)
    {
        return $type;
    }

    /**
     * @param StringType $type
     *
     * @return Type
     */
    public function visitStringType(StringType $type)
    {
        return $type;
    }

    /**
     * @param StringableType $type
     *
     * @return Type
     */
    public function visitStringableType(StringableType $type)
    {
        return $type;
    }

    /**
     * @param TraversableType $type
     *
     * @return Type
     */
    public function visitTraversableType(TraversableType $type)
    {
        return new TraversableType(
            $type->primaryType()->accept($this),
            $type->keyType()->accept($this),
            $type->valueType()->accept($this)
        );
    }

    /**
     * @param TupleType $type
     *
     * @return Type
     */
    public function visitTupleType(TupleType $type)
    {
        $types = array();
        foreach ($type->types() as $subType) {
            $types[] = $subType->accept($this);
        }

        return new TupleType($types);
    }

    private $classNameResolver;
}
