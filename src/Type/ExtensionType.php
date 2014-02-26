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

use Eloquent\Cosmos\ClassName\ClassNameInterface;

/**
 * Represents an extension type.
 */
class ExtensionType extends AbstractCompositeType implements
    ConfigurableTypeInterface
{
    /**
     * Construct a new extension type.
     *
     * @param ClassNameInterface                $className  The extension class name.
     * @param array<integer,TypeInterface>|null $types      The sub-types.
     * @param array<string,mixed>|null          $attributes The attributes.
     */
    public function __construct(
        ClassNameInterface $className,
        array $types = null,
        array $attributes = null
    ) {
        if (null === $attributes) {
            $attributes = array();
        }

        parent::__construct($types);

        $this->className = $className;
        $this->attributes = $attributes;
    }

    /**
     * Get the extension class name.
     *
     * @return ClassNameInterface The extension class name.
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * Get the attributes.
     *
     * @return array<string,mixed> The attributes.
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Accept a visitor.
     *
     * @param VisitorInterface $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visitExtensionType($this);
    }

    private $className;
    private $attributes;
}
