<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser\Exception;

use Exception;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Typhax\Parser\Exception\UnsupportedAttributeException
 * @covers \Eloquent\Typhax\Parser\Exception\AbstractParseException
 */
class UnsupportedAttributeExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $typeName = 'foo';
        $attribute = 'bar';
        $offset = 666;
        $previous = new Exception();
        $exception = new UnsupportedAttributeException($typeName, $attribute, $offset, $previous);
        $expectedMessage = "Unsupported attribute at offset 666. Type 'foo' does not support attribute 'bar'.";

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($typeName, $exception->typeName());
        $this->assertSame($attribute, $exception->attribute());
        $this->assertSame($offset, $exception->offset());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
