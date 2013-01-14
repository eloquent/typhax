<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Icecave\Visita\Host;

class StreamType extends Host implements Type
{
    /**
     * @param boolean|null $readable
     * @param boolean|null $writable
     */
    public function __construct($readable = null, $writable = null)
    {
        $this->readable = $readable;
        $this->writable = $writable;
    }

    /**
     * @return boolean|null
     */
    public function readable()
    {
        return $this->readable;
    }

    /**
     * @return boolean|null
     */
    public function writable()
    {
        return $this->writable;
    }

    private $readable;
    private $writable;
}
