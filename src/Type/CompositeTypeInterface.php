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
 * The interface implemented by composite types.
 */
interface CompositeTypeInterface extends TypeInterface
{
    /**
     * Get the sub-types.
     *
     * @return array<integer,TypeInterface> The sub-types.
     */
    public function types();
}
