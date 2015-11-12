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
use Eloquent\Typhax\Type\TypeVisitor;

/**
 * Renders types using Typhax syntax, without whitespace.
 *
 * @api
 */
class CondensedTypeRenderer implements TypeRenderer, TypeVisitor
{
    /**
     * Create a new condensed type renderer.
     *
     * @api
     *
     * @return self The renderer.
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Render the supplied type.
     *
     * @api
     *
     * @param Type $type The type.
     *
     * @return string The rendered type.
     */
    public function render(Type $type)
    {
        return $type->accept($this);
    }

    /**
     * Visit an and type.
     *
     * @param AndType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitAndType(AndType $type)
    {
        $subTypes = array();

        foreach ($type->types() as $subType) {
            $subTypes[] = $subType->accept($this);
        }

        return implode('+', $subTypes);
    }

    /**
     * Visit an array type.
     *
     * @param ArrayType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitArrayType(ArrayType $type)
    {
        return 'array';
    }

    /**
     * Visit a boolean type.
     *
     * @param BooleanType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitBooleanType(BooleanType $type)
    {
        return 'boolean';
    }

    /**
     * Visit a callable type.
     *
     * @param CallableType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitCallableType(CallableType $type)
    {
        return 'callable';
    }

    /**
     * Visit an extension type.
     *
     * @param ExtensionType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitExtensionType(ExtensionType $type)
    {
        $string = ':' . $type->className();

        if (0 !== count($type->types())) {
            $subTypes = array();

            foreach ($type->types() as $subType) {
                $subTypes[] = $subType->accept($this);
            }

            $string .= sprintf('<%s>', implode(',', $subTypes));
        }

        if (0 !== count($type->attributes())) {
            $attributes = array();

            foreach ($type->attributes() as $key => $value) {
                $attributes[] = $key . ':' . var_export($value, true);
            }

            $string .= sprintf('{%s}', implode(',', $attributes));
        }

        return $string;
    }

    /**
     * Visit a float type.
     *
     * @param FloatType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitFloatType(FloatType $type)
    {
        return 'float';
    }

    /**
     * Visit an integer type.
     *
     * @param IntegerType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitIntegerType(IntegerType $type)
    {
        return 'integer';
    }

    /**
     * Visit a mixed type.
     *
     * @param MixedType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitMixedType(MixedType $type)
    {
        return 'mixed';
    }

    /**
     * Visit a null type.
     *
     * @param NullType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitNullType(NullType $type)
    {
        return 'null';
    }

    /**
     * Visit a numeric type.
     *
     * @param NumericType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitNumericType(NumericType $type)
    {
        return 'numeric';
    }

    /**
     * Visit an object type.
     *
     * @param ObjectType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitObjectType(ObjectType $type)
    {
        if (null !== $type->ofType()) {
            return $type->ofType();
        }

        return 'object';
    }

    /**
     * Visit an or type.
     *
     * @param OrType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitOrType(OrType $type)
    {
        $subTypes = array();

        foreach ($type->types() as $subType) {
            $subTypes[] = $subType->accept($this);
        }

        return implode('|', $subTypes);
    }

    /**
     * Visit a resource type.
     *
     * @param ResourceType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitResourceType(ResourceType $type)
    {
        $attributes = '';
        if (null !== $type->ofType()) {
            $attributes =
                sprintf('{ofType:%s}', var_export($type->ofType(), true));
        }

        return 'resource' . $attributes;
    }

    /**
     * Visit a stream type.
     *
     * @param StreamType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitStreamType(StreamType $type)
    {
        $attributes = '';

        if (null !== $type->readable()) {
            if ($type->readable()) {
                $attributes = 'readable:true';
            } else {
                $attributes = 'readable:false';
            }
        }

        if (null !== $type->writable()) {
            if ('' !== $attributes) {
                $attributes .= ',';
            }

            if ($type->writable()) {
                $attributes .= 'writable:true';
            } else {
                $attributes .= 'writable:false';
            }
        }

        if ('' !== $attributes) {
            $attributes = sprintf(
                '{%s}',
                $attributes
            );
        }

        return 'stream' . $attributes;
    }

    /**
     * Visit a string type.
     *
     * @param StringType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitStringType(StringType $type)
    {
        return 'string';
    }

    /**
     * Visit a stringable type.
     *
     * @param StringableType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitStringableType(StringableType $type)
    {
        return 'stringable';
    }

    /**
     * Visit a traversable type.
     *
     * @param TraversableType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitTraversableType(TraversableType $type)
    {
        $primaryType = $type->primaryType()->accept($this);

        $subTypes = array();

        if ($type->keyType()) {
            $subTypes[] = $type->keyType()->accept($this);
            $subTypes[] = $type->valueType()->accept($this);
        } elseif (
            !$type->primaryType() instanceof ArrayType ||
            !$type->valueType() instanceof MixedType
        ) {
            $subTypes[] = $type->valueType()->accept($this);
        }

        if (count($subTypes) < 1) {
            return $primaryType;
        }

        return sprintf('%s<%s>', $primaryType, implode(',', $subTypes));
    }

    /**
     * Visit a tuple type.
     *
     * @param TupleType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitTupleType(TupleType $type)
    {
        $subTypes = array();

        foreach ($type->types() as $subType) {
            $subTypes[] = $subType->accept($this);
        }

        return sprintf('tuple<%s>', implode(',', $subTypes));
    }
}
