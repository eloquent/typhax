<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @codeCoverageIgnoreStart

namespace Eloquent\Typhax\AST;

interface Visitor
{
    /**
     * @param Composite
     *
     * @return mixed
     */
    public function visitComposite(Composite $composite);

    /**
     * @param Type\ArrayType
     *
     * @return mixed
     */
    public function visitArrayType(Type\ArrayType $type);

    /**
     * @param Type\BooleanType
     *
     * @return mixed
     */
    public function visitBooleanType(Type\BooleanType $type);

    /**
     * @param Type\CallbackType
     *
     * @return mixed
     */
    public function visitCallbackType(Type\CallbackType $type);

    /**
     * @param Type\FloatType
     *
     * @return mixed
     */
    public function visitFloatType(Type\FloatType $type);

    /**
     * @param Type\IntegerType
     *
     * @return mixed
     */
    public function visitIntegerType(Type\IntegerType $type);

    /**
     * @param Type\MixedType
     *
     * @return mixed
     */
    public function visitMixedType(Type\MixedType $type);

    /**
     * @param Type\NumberType
     *
     * @return mixed
     */
    public function visitNumberType(Type\NumberType $type);

    /**
     * @param Type\NumericType
     *
     * @return mixed
     */
    public function visitNumericType(Type\NumericType $type);

    /**
     * @param Type\ObjectType
     *
     * @return mixed
     */
    public function visitObjectType(Type\ObjectType $type);

    /**
     * @param Type\ResourceType
     *
     * @return mixed
     */
    public function visitResourceType(Type\ResourceType $type);

    /**
     * @param Type\StringType
     *
     * @return mixed
     */
    public function visitStringType(Type\StringType $type);
}
