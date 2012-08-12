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

use Icecave\Visita\IVisitor;

interface Visitor extends IVisitor
{
    /**
     * @param AndType $type
     *
     * @return mixed
     */
    public function visitAndType(AndType $type);

    /**
     * @param ArrayType $type
     *
     * @return mixed
     */
    public function visitArrayType(ArrayType $type);

    /**
     * @param BooleanType $type
     *
     * @return mixed
     */
    public function visitBooleanType(BooleanType $type);

    /**
     * @param CallableType $type
     *
     * @return mixed
     */
    public function visitCallableType(CallableType $type);

    /**
     * @param FloatType $type
     *
     * @return mixed
     */
    public function visitFloatType(FloatType $type);

    /**
     * @param IntegerType $type
     *
     * @return mixed
     */
    public function visitIntegerType(IntegerType $type);

    /**
     * @param MixedType $type
     *
     * @return mixed
     */
    public function visitMixedType(MixedType $type);

    /**
     * @param NullType $type
     *
     * @return mixed
     */
    public function visitNullType(NullType $type);

    /**
     * @param NumericType $type
     *
     * @return mixed
     */
    public function visitNumericType(NumericType $type);

    /**
     * @param ObjectType $type
     *
     * @return mixed
     */
    public function visitObjectType(ObjectType $type);

    /**
     * @param OrType $type
     *
     * @return mixed
     */
    public function visitOrType(OrType $type);

    /**
     * @param ResourceType $type
     *
     * @return mixed
     */
    public function visitResourceType(ResourceType $type);

    /**
     * @param StreamType $type
     *
     * @return mixed
     */
    public function visitStreamType(StreamType $type);

    /**
     * @param StringType $type
     *
     * @return mixed
     */
    public function visitStringType(StringType $type);

    /**
     * @param StringableType $type
     *
     * @return mixed
     */
    public function visitStringableType(StringableType $type);

    /**
     * @param TraversableType $type
     *
     * @return mixed
     */
    public function visitTraversableType(TraversableType $type);

    /**
     * @param TupleType $type
     *
     * @return mixed
     */
    public function visitTupleType(TupleType $type);
}
