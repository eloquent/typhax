<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Comparator;

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
 * A visitor that compares types for equivalence.
 */
class TypeEquivalenceComparatorVisitor implements TypeVisitor
{
    /**
     * Construct a new type equivalence comparator visitor.
     *
     * @param TypeEquivalenceComparator $comparator The comparator.
     * @param Type                      $type       The type.
     */
    public function __construct(
        TypeEquivalenceComparator $comparator,
        Type $type
    ) {
        $this->comparator = $comparator;
        $this->type = $type;
        $this->class = get_class($type);
        $this->isMixed = $type instanceof MixedType;
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
        return $this->compareComposite($type);
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
        return $this->comparePrimaryType($type);
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
        return $this->comparePrimaryType($type);
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
        return $this->comparePrimaryType($type);
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
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        $difference = strcmp($this->type->className(), $type->className());

        if (0 !== $difference) {
            return $difference;
        }

        $difference = $this->compareTypeList($type->types(), true, false);

        if (0 !== $difference) {
            return $difference;
        }

        $left = $this->type->attributes();
        $right = $type->attributes();

        if ($left < $right) {
            return -1;
        } elseif ($left > $right) {
            return 1;
        }

        $left = array_keys($left);
        $right = array_keys($right);

        if ($left < $right) {
            return -1;
        } elseif ($left > $right) {
            return 1;
        }

        return 0;
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
        return $this->comparePrimaryType($type);
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
        return $this->comparePrimaryType($type);
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
        if ($this->isMixed) {
            return 0;
        }

        return 1;
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
        return $this->comparePrimaryType($type);
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
        return $this->comparePrimaryType($type);
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
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareAttribute($this->type->ofType(), $type->ofType());
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
        return $this->compareComposite($type);
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
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareAttribute($this->type->ofType(), $type->ofType());
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
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        $difference =
            $this->compareAttribute($this->type->readable(), $type->readable());

        if (0 !== $difference) {
            return $difference;
        }

        return
            $this->compareAttribute($this->type->writable(), $type->writable());
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
        return $this->comparePrimaryType($type);
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
        return $this->comparePrimaryType($type);
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
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        $difference = $this->comparator
            ->compare($this->type->primaryType(), $type->primaryType());

        if (0 !== $difference) {
            return $difference;
        }

        $leftKeyType = $this->type->keyType();
        $rightKeyType = $type->keyType();

        if ($leftKeyType) {
            if ($rightKeyType) {
                $difference =
                    $this->comparator->compare($leftKeyType, $rightKeyType);

                if (0 !== $difference) {
                    return $difference;
                }
            } else {
                return 1;
            }
        } elseif ($rightKeyType) {
            return -1;
        }

        return $this->comparator
            ->compare($this->type->valueType(), $type->valueType());
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
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareTypeList($type->types(), true, false);
    }

    private function comparePrimaryType($type)
    {
        if ($this->isMixed) {
            return -1;
        }

        return strcmp($this->class, get_class($type));
    }

    private function compareComposite($type)
    {
        $difference = $this->comparePrimaryType($type);

        if (0 !== $difference) {
            return $difference;
        }

        return $this->compareTypeList($type->types(), false, true);
    }

    private function compareTypeList($rightTypes, $compareOrder, $unique)
    {
        $leftTypes = $this->type->types();

        if ($unique) {
            $leftTypes = $this->uniqueTypes($leftTypes);
            $rightTypes = $this->uniqueTypes($rightTypes);
        }

        $leftTypeCount = count($leftTypes);
        $rightTypeCount = count($rightTypes);

        if ($leftTypeCount < $rightTypeCount) {
            return -1;
        }

        if ($leftTypeCount > $rightTypeCount) {
            return 1;
        }

        if (!$compareOrder) {
            usort($leftTypes, array($this->comparator, 'compare'));
            usort($rightTypes, array($this->comparator, 'compare'));
        }

        for ($i = 0; $i < $leftTypeCount; ++$i) {
            $leftType = $leftTypes[$i];
            $rightType = $rightTypes[$i];

            $difference = $this->comparator->compare($leftType, $rightType);

            if (0 !== $difference) {
                return $difference;
            }
        }

        return 0;
    }

    private function compareAttribute($left, $right)
    {
        if (null === $left && null !== $right) {
            return -1;
        }

        if (null !== $left && null === $right) {
            return 1;
        }

        return strcmp(strval($left), strval($right));
    }

    private function uniqueTypes(array $types)
    {
        $unique = array();

        foreach ($types as $type) {
            if (!$this->typeInArray($type, $unique)) {
                $unique[] = $type;
            }
        }

        return $unique;
    }

    private function typeInArray(Type $type, array $types)
    {
        foreach ($types as $thisType) {
            if ($this->comparator->isEquivalent($thisType, $type)) {
                return true;
            }
        }

        return false;
    }

    private $comparator;
    private $type;
    private $class;
    private $isMixed;
}
