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
 * Represents a stream type.
 */
class StreamType implements ConfigurableTypeInterface
{
    /**
     * Construct a new stream type.
     *
     * @param boolean|null $readable The required readability status, or null if readability is irrelevant.
     * @param boolean|null $writable The required writability status, or null if writability is irrelevant.
     */
    public function __construct($readable = null, $writable = null)
    {
        $this->readable = $readable;
        $this->writable = $writable;
    }

    /**
     * Get the required readability status.
     *
     * @return boolean|null The required readability status, or null if readability is irrelevant.
     */
    public function readable()
    {
        return $this->readable;
    }

    /**
     * Get the required writability status.
     *
     * @return boolean|null The required writability status, or null if writability is irrelevant.
     */
    public function writable()
    {
        return $this->writable;
    }

    /**
     * Get the attributes.
     *
     * @return array<string,mixed> The attributes.
     */
    public function attributes()
    {
        $attributes = array();
        if (null !== $this->readable()) {
            $attributes['readable'] = $this->readable();
        }
        if (null !== $this->writable()) {
            $attributes['writable'] = $this->writable();
        }

        return $attributes;
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
        return $visitor->visitStreamType($this);
    }

    private $readable;
    private $writable;
}
