<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Comparator;

use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\CompositeType;
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
use Eloquent\Typhax\Type\Type;
use Eloquent\Typhax\Type\Visitor;
use ReflectionObject;

class TypeEquivalenceComparator implements Visitor
{
    /**
     * @param Type $left
     * @param Type $right
     *
     * @return integer
     */
    public static function compare(Type $left, Type $right)
    {
        return $right->accept(new static($left));
    }

    /**
     * @param Type $left
     * @param Type $right
     *
     * @return boolean
     */
    public static function equivalent(Type $left, Type $right)
    {
        return 0 === static::compare($left, $right);
    }

    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @param AndType $type
     *
     * @return integer
     */
    public function visitAndType(AndType $type)
    {
        return $this->compareComposite($type);
    }

    /**
     * @param ArrayType $type
     *
     * @return integer
     */
    public function visitArrayType(ArrayType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param BooleanType $type
     *
     * @return integer
     */
    public function visitBooleanType(BooleanType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param CallableType $type
     *
     * @return integer
     */
    public function visitCallableType(CallableType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param FloatType $type
     *
     * @return integer
     */
    public function visitFloatType(FloatType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param IntegerType $type
     *
     * @return integer
     */
    public function visitIntegerType(IntegerType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param MixedType $type
     *
     * @return integer
     */
    public function visitMixedType(MixedType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param NullType $type
     *
     * @return integer
     */
    public function visitNullType(NullType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param NumericType $type
     *
     * @return integer
     */
    public function visitNumericType(NumericType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param ObjectType $type
     *
     * @return integer
     */
    public function visitObjectType(ObjectType $type)
    {
        $difference = $this->compareClass($type);
        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareAttribute(
            $this->type()->ofType(),
            $type->ofType()
        );
    }

    /**
     * @param OrType $type
     *
     * @return integer
     */
    public function visitOrType(OrType $type)
    {
        return $this->compareComposite($type);
    }

    /**
     * @param ResourceType $type
     *
     * @return integer
     */
    public function visitResourceType(ResourceType $type)
    {
        $difference = $this->compareClass($type);
        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareAttribute(
            $this->type()->ofType(),
            $type->ofType()
        );
    }

    /**
     * @param StreamType $type
     *
     * @return integer
     */
    public function visitStreamType(StreamType $type)
    {
        $difference = $this->compareClass($type);
        if (0 !== $difference) {
            return $difference;
        }

        $difference = $this->compareAttribute(
            $this->type()->readable(),
            $type->readable()
        );
        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareAttribute(
            $this->type()->writable(),
            $type->writable()
        );
    }

    /**
     * @param StringType $type
     *
     * @return integer
     */
    public function visitStringType(StringType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param StringableType $type
     *
     * @return integer
     */
    public function visitStringableType(StringableType $type)
    {
        return $this->compareClass($type);
    }

    /**
     * @param TraversableType $type
     *
     * @return integer
     */
    public function visitTraversableType(TraversableType $type)
    {
        $difference = $this->compareClass($type);
        if (0 !== $difference) {
            return $difference;
        }

        $difference = static::compare(
            $this->type()->primaryType(),
            $type->primaryType()
        );
        if (0 !== $difference) {
            return $difference;
        }

        $difference = static::compare(
            $this->type()->keyType(),
            $type->keyType()
        );
        if (0 !== $difference) {
            return $difference;
        }

        return static::compare(
            $this->type()->valueType(),
            $type->valueType()
        );
    }

    /**
     * @param TupleType $type
     *
     * @return integer
     */
    public function visitTupleType(TupleType $type)
    {
        $difference = $this->compareClass($type);
        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareTypeList($type->types(), true, false);
    }

    /**
     * @param Type $type
     *
     * @return integer
     */
    protected function compareClass(Type $type)
    {
        $leftReflector = new ReflectionObject($this->type());
        $rightReflector = new ReflectionObject($type);

        return strcmp(
            $leftReflector->getName(),
            $rightReflector->getName()
        );
    }

    /**
     * @param Type $type
     *
     * @return integer
     */
    protected function compareComposite(CompositeType $type)
    {
        $difference = $this->compareClass($type);
        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareTypeList($type->types(), false, true);
    }

    /**
     * @param array<Type> $types
     * @param boolean $compareOrder
     * @param boolean $unique
     *
     * @return integer
     */
    protected function compareTypeList(array $rightTypes, $compareOrder, $unique)
    {
        $leftTypes = $this->type()->types();

        if ($unique) {
            $leftTypes = $this->uniqueTypes($leftTypes);
            $rightTypes = $this->uniqueTypes($rightTypes);
        }

        $leftTypeCount = count($leftTypes);
        $rightTypeCount = count($rightTypes);
        $typeCount =
            $leftTypeCount > $rightTypeCount ?
            $leftTypeCount :
            $rightTypeCount
        ;

        if (!$compareOrder) {
            usort($leftTypes, __NAMESPACE__.'\TypeEquivalenceComparator::compare');
            usort($rightTypes, __NAMESPACE__.'\TypeEquivalenceComparator::compare');
        }

        for ($i = 0; $i < $typeCount; $i ++) {
            $leftType = array_key_exists($i, $leftTypes) ? $leftTypes[$i] : null;
            $rightType = array_key_exists($i, $rightTypes) ? $rightTypes[$i] : null;

            if (
                null === $leftType &&
                null !== $rightType
            ) {
                return -100;
            }
            if (
                null !== $leftType &&
                null === $rightType
            ) {
                return 100;
            }

            $difference = static::compare($leftType, $rightType);
            if (0 !== $difference) {
                return $difference;
            }
        }

        return 0;
    }

    /**
     * @param mixed|null $left
     * @param mixed|null $right
     *
     * @return integer
     */
    protected function compareAttribute($left, $right)
    {
        if (
            null === $left &&
            null !== $right
        ) {
            return -100;
        }

        if (
            null !== $left &&
            null === $right
        ) {
            return 100;
        }

        return strcmp(
            strval($left),
            strval($right)
        );
    }

    /**
     * @param array<Type> $types
     *
     * @return array<Type>
     */
    protected function uniqueTypes(array $types) {
        $unique = array();
        foreach ($types as $type) {
            if (!$this->typeInArray($type, $unique)) {
                $unique[] = $type;
            }
        }

        return $unique;
    }

    /**
     * @param Type $type
     * @param array<Type> $types
     *
     * @return boolean
     */
    protected function typeInArray(Type $type, array $types) {
        foreach ($types as $thisType) {
            if (static::equivalent($thisType, $type)) {
                return true;
            }
        }

        return false;
    }

    private $type;
}
