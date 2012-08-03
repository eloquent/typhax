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

namespace Eloquent\Typhax\Type;

interface Visitor
{
    /**
     * @param AndType
     *
     * @return mixed
     */
    public function visitAndType(AndType $type);

    /**
     * @param ArrayType
     *
     * @return mixed
     */
    public function visitArrayType(ArrayType $type);

    /**
     * @param BooleanType
     *
     * @return mixed
     */
    public function visitBooleanType(BooleanType $type);

    /**
     * @param CallbackType
     *
     * @return mixed
     */
    public function visitCallbackType(CallbackType $type);

    /**
     * @param FloatType
     *
     * @return mixed
     */
    public function visitFloatType(FloatType $type);

    /**
     * @param IntegerType
     *
     * @return mixed
     */
    public function visitIntegerType(IntegerType $type);

    /**
     * @param MixedType
     *
     * @return mixed
     */
    public function visitMixedType(MixedType $type);

    /**
     * @param NullType
     *
     * @return mixed
     */
    public function visitNullType(NullType $type);

    /**
     * @param ObjectType
     *
     * @return mixed
     */
    public function visitObjectType(ObjectType $type);

    /**
     * @param OrType
     *
     * @return mixed
     */
    public function visitOrType(OrType $type);

    /**
     * @param ResourceType
     *
     * @return mixed
     */
    public function visitResourceType(ResourceType $type);

    /**
     * @param StringType
     *
     * @return mixed
     */
    public function visitStringType(StringType $type);

    /**
     * @param TraversableType
     *
     * @return mixed
     */
    public function visitTraversableType(TraversableType $type);

    /**
     * @param TupleType
     *
     * @return mixed
     */
    public function visitTupleType(TupleType $type);
}
