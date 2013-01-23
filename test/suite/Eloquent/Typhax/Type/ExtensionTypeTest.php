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

class ExtensionTypeTest extends PHPUnit_Framework_TestCase
{
    public function testExtensionType()
    {
        $className = ClassName::fromString('foo');
        $subTypes = array(new IntegerType);
        $attributes = array('foo' => 'bar');

        $type = new ExtensionType($className, $subTypes, $attributes);

        $this->assertSame($className, $type->className());
        $this->assertSame($subTypes, $type->subTypes());
        $this->assertSame($attributes, $type->attributes());
    }
}
