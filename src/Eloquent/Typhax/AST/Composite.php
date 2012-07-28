<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST;

class Composite implements Node
{
    /**
     * @param string $separator
     */
    public function __construct($separator)
    {
        $this->separator = $separator;
    }

    /**
     * @return string
     */
    public function separator()
    {
        return $this->separator;
    }

    /**
     * @param Node $type
     */
    public function addType(Node $type)
    {
        $this->types[] = $type;
    }

    /**
     * @return array<integer,Node>
     */
    public function types()
    {
        return $this->types;
    }

    /**
     * @param Visitor $visitor
     *
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        return $visitor->visitComposite($this);
    }

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var array<integer,Node>
     */
    protected $types = array();
}
