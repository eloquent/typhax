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
 * @covers \Eloquent\Typhax\Parser\Exception\UnexpectedTokenException
 * @covers \Eloquent\Typhax\Parser\Exception\AbstractParseException
 */
class UnexpectedTokenExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $unexpected = 'foo';
        $offset = 666;
        $expected = array('bar');
        $previous = new Exception();
        $exception = new UnexpectedTokenException($unexpected, $offset, $expected, $previous);
        $expectedMessage = 'Unexpected foo at offset 666. Expected bar.';

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($unexpected, $exception->unexpected());
        $this->assertSame($expected, $exception->expected());
        $this->assertSame($offset, $exception->offset());
        $this->assertSame($previous, $exception->getPrevious());

        $unexpected = 'foo';
        $offset = 666;
        $expected = array('bar', 'baz');
        $previous = new Exception();
        $exception = new UnexpectedTokenException($unexpected, $offset, $expected, $previous);
        $expectedMessage = 'Unexpected foo at offset 666. Expected one of bar, baz.';

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($unexpected, $exception->unexpected());
        $this->assertSame($expected, $exception->expected());
        $this->assertSame($offset, $exception->offset());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
