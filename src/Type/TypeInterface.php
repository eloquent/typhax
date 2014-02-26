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
 * The interface implemented by types.
 */
interface TypeInterface
{
    /**
     * Accept a visitor.
     *
     * @param VisitorInterface $visitor The visitor.
     *
     * @return mixed The result of visitation.
     */
    public function accept(VisitorInterface $visitor);
}
