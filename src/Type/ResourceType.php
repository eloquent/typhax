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
 * Represents a resource type.
 */
class ResourceType implements ConfigurableTypeInterface
{
    /**
     * Construct a new resource type.
     *
     * @param string|null $ofType The resource type, or null if the resource can be any type.
     */
    public function __construct($ofType = null)
    {
        $this->ofType = $ofType;
    }

    /**
     * Get the resource type.
     *
     * @return string|null The resource type, or null if the resource can be any type.
     */
    public function ofType()
    {
        return $this->ofType;
    }

    /**
     * Get the attributes.
     *
     * @return array<string,mixed> The attributes.
     */
    public function attributes()
    {
        if (null === $this->type()) {
            return array();
        }

        return array('type' => $this->type());
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
        return $visitor->visitResourceType($this);
    }

    private $ofType;
}
