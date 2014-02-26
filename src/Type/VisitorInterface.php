<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

/**
 * The interface implemented by Typhax type visitors.
 */
interface VisitorInterface
{
    /**
     * Visit an and type.
     *
     * @param AndType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitAndType(AndType $type);

    /**
     * Visit an array type.
     *
     * @param ArrayType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitArrayType(ArrayType $type);

    /**
     * Visit a boolean type.
     *
     * @param BooleanType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitBooleanType(BooleanType $type);

    /**
     * Visit a callable type.
     *
     * @param CallableType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitCallableType(CallableType $type);

    /**
     * Visit an extension type.
     *
     * @param ExtensionType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitExtensionType(ExtensionType $type);

    /**
     * Visit a float type.
     *
     * @param FloatType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitFloatType(FloatType $type);

    /**
     * Visit an integer type.
     *
     * @param IntegerType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitIntegerType(IntegerType $type);

    /**
     * Visit a mixed type.
     *
     * @param MixedType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitMixedType(MixedType $type);

    /**
     * Visit a null type.
     *
     * @param NullType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitNullType(NullType $type);

    /**
     * Visit a numeric type.
     *
     * @param NumericType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitNumericType(NumericType $type);

    /**
     * Visit an object type.
     *
     * @param ObjectType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitObjectType(ObjectType $type);

    /**
     * Visit an or type.
     *
     * @param OrType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitOrType(OrType $type);

    /**
     * Visit a resource type.
     *
     * @param ResourceType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitResourceType(ResourceType $type);

    /**
     * Visit a stream type.
     *
     * @param StreamType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitStreamType(StreamType $type);

    /**
     * Visit a string type.
     *
     * @param StringType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitStringType(StringType $type);

    /**
     * Visit a stringable type.
     *
     * @param StringableType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitStringableType(StringableType $type);

    /**
     * Visit a traversable type.
     *
     * @param TraversableType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitTraversableType(TraversableType $type);

    /**
     * Visit a tuple type.
     *
     * @param TupleType $type The type.
     *
     * @return mixed The result of visitation.
     */
    public function visitTupleType(TupleType $type);
}
