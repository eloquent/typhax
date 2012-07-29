<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST\Type;

use Phake;

class StringTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $type = new StringType;
        $visitor = Phake::mock('Eloquent\Typhax\AST\Visitor');
        $type->accept($visitor);

        Phake::verify($visitor)->visitStringType(
            $this->identicalTo($type)
        );
    }
}
