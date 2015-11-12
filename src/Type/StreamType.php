<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

/**
 * Represents a stream type.
 *
 * @api
 */
class StreamType implements Type
{
    /**
     * Construct a new stream type.
     *
     * @param boolean|null $readable The readable condition.
     * @param boolean|null $writable The writable condition.
     */
    public function __construct($readable = null, $writable = null)
    {
        $this->readable = $readable;
        $this->writable = $writable;
    }

    /**
     * Get the readable condition.
     *
     * @api
     *
     * @return boolean|null The readable condition.
     */
    public function readable()
    {
        return $this->readable;
    }

    /**
     * Get the writable condition.
     *
     * @api
     *
     * @return boolean|null The writable condition.
     */
    public function writable()
    {
        return $this->writable;
    }

    /**
     * Accept a visitor.
     *
     * @api
     *
     * @param TypeVisitor $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(TypeVisitor $visitor)
    {
        return $visitor->visitStreamType($this);
    }

    private $readable;
    private $writable;
}
