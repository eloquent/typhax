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
 * The interface implemented by configurable types.
 */
interface ConfigurableTypeInterface extends TypeInterface
{
    /**
     * Get the attributes.
     *
     * @return array<string,mixed> The attributes.
     */
    public function attributes();
}
