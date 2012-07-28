<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @codeCoverageIgnoreStart

namespace Eloquent\Typhax\AST;

interface Visitor
{
    /**
     * @param Composite
     *
     * @return mixed
     */
    public function visitComposite(Composite $composite);

    /**
     * @param Type
     *
     * @return mixed
     */
    public function visitType(Type $type);
}