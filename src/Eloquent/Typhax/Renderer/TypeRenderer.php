<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Visitor;

class TypeRenderer implements Visitor
{
    /**
     * @param AndType $type
     *
     * @return string
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
     * @param ArrayType $type
     *
     * @return string
     */
    public function visitArrayType(ArrayType $type)
    {
        return 'array';
    }

    /**
     * @param BooleanType $type
     *
     * @return string
     */
    public function visitBooleanType(BooleanType $type)
    {
        return 'boolean';
    }

    /**
     * @param CallableType $type
     *
     * @return string
     */
    public function visitCallableType(CallableType $type)
    {
        return 'callable';
    }

    /**
     * @param ExtensionType $type
     *
     * @return mixed
     */
    public function visitExtensionType(ExtensionType $type)
    {
        $string = ':'.$type->className()->toRelative()->string();

        if (0 !== count($type->types())) {
            $subTypes = array();
            foreach ($type->types() as $subType) {
                $subTypes[] = $subType->accept($this);
            }

            $string .= sprintf(
                '<%s>',
                implode(', ', $subTypes)
            );
        }

        if (0 !== count($type->attributes())) {
            $attributes = array();
            foreach ($type->attributes() as $key => $value) {
                $attributes[] = $key.': '.var_export($value, true);
            }

            $string .= sprintf(
                ' {%s}',
                implode(', ', $attributes)
            );
        }

        return $string;
    }

    /**
     * @param FloatType $type
     *
     * @return string
     */
    public function visitFloatType(FloatType $type)
    {
        return 'float';
    }

    /**
     * @param IntegerType $type
     *
     * @return string
     */
    public function visitIntegerType(IntegerType $type)
    {
        return 'integer';
    }

    /**
     * @param MixedType $type
     *
     * @return string
     */
    public function visitMixedType(MixedType $type)
    {
        return 'mixed';
    }

    /**
     * @param NullType $type
     *
     * @return string
     */
    public function visitNullType(NullType $type)
    {
        return 'null';
    }

    /**
     * @param NumericType $type
     *
     * @return string
     */
    public function visitNumericType(NumericType $type)
    {
        return 'numeric';
    }

    /**
     * @param ObjectType $type
     *
     * @return string
     */
    public function visitObjectType(ObjectType $type)
    {
        if (null !== $type->ofType()) {
            return $type->ofType()->toRelative()->string();
        }

        return 'object';
    }

    /**
     * @param OrType $type
     *
     * @return string
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
     * @param ResourceType $type
     *
     * @return string
     */
    public function visitResourceType(ResourceType $type)
    {
        $attributes = '';
        if (null !== $type->ofType()) {
            $attributes = sprintf(
                ' {ofType: %s}',
                var_export($type->ofType(), true)
            );
        }

        return 'resource'.$attributes;
    }

    /**
     * @param StreamType $type
     *
     * @return string
     */
    public function visitStreamType(StreamType $type)
    {
        $attributes = '';
        if (null !== $type->readable()) {
            if ($type->readable()) {
                $attributes = 'readable: true';
            } else {
                $attributes = 'readable: false';
            }
        }
        if (null !== $type->writable()) {
            if ('' !== $attributes) {
                $attributes .= ', ';
            }

            if ($type->writable()) {
                $attributes .= 'writable: true';
            } else {
                $attributes .= 'writable: false';
            }
        }
        if ('' !== $attributes) {
            $attributes = sprintf(
                ' {%s}',
                $attributes
            );
        }

        return 'stream'.$attributes;
    }

    /**
     * @param StringType $type
     *
     * @return string
     */
    public function visitStringType(StringType $type)
    {
        return 'string';
    }

    /**
     * @param StringableType $type
     *
     * @return string
     */
    public function visitStringableType(StringableType $type)
    {
        return 'stringable';
    }

    /**
     * @param TraversableType $type
     *
     * @return string
     */
    public function visitTraversableType(TraversableType $type)
    {
        $primaryType = $type->primaryType()->accept($this);

        $subTypes = array();
        if (!$type->keyType() instanceof MixedType) {
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

        return sprintf(
            '%s<%s>',
            $primaryType,
            implode(', ', $subTypes)
        );
    }

    /**
     * @param TupleType $type
     *
     * @return string
     */
    public function visitTupleType(TupleType $type)
    {
        $subTypes = array();
        foreach ($type->types() as $subType) {
            $subTypes[] = $subType->accept($this);
        }

        return sprintf(
            'tuple<%s>',
            implode(', ', $subTypes)
        );
    }
}