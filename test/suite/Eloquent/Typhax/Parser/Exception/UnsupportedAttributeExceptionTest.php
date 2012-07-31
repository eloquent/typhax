<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser\Exception;

class UnsupportedAttributeExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $typeName = 'foo';
        $attribute = 'bar';
        $position = 666;
        $previous = new \Exception;
        $exception = new UnsupportedAttributeException($typeName, $attribute, $position, $previous);
        $expectedMessage = "Unsupported attribute at position 666. Type 'foo' does not support attribute 'bar'.";

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($typeName, $exception->typeName());
        $this->assertSame($attribute, $exception->attribute());
        $this->assertSame($position, $exception->position());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
