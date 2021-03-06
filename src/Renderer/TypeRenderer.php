<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Renderer;

use Eloquent\Typhax\Type\Type;

/**
 * The interface implemented by type renderers.
 *
 * @api
 */
interface TypeRenderer
{
    /**
     * Render the supplied type.
     *
     * @api
     *
     * @param Type $type The type.
     *
     * @return string The rendered type.
     */
    public function render(Type $type);
}
