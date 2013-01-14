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

use Eloquent\Cosmos\ClassName;
use PHPUnit_Framework_TestCase;

class ObjectTypeTest extends PHPUnit_Framework_TestCase
{
    public function testOfType()
    {
        $className = ClassName::fromString('foo');
        $type = new ObjectType($className);

        $this->assertSame($className, $type->ofType());
    }
}
