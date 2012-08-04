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

use PHPUnit_Framework_TestCase;

class UnexpectedTokenTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $unexpected = 'foo';
        $position = 666;
        $expected = array('bar');
        $previous = new \Exception;
        $exception = new UnexpectedTokenException($unexpected, $position, $expected, $previous);
        $expectedMessage = 'Unexpected foo at position 666. Expected bar.';

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($unexpected, $exception->unexpected());
        $this->assertSame($expected, $exception->expected());
        $this->assertSame($position, $exception->position());
        $this->assertSame($previous, $exception->getPrevious());


        $unexpected = 'foo';
        $position = 666;
        $expected = array('bar', 'baz');
        $previous = new \Exception;
        $exception = new UnexpectedTokenException($unexpected, $position, $expected, $previous);
        $expectedMessage = 'Unexpected foo at position 666. Expected one of bar, baz.';

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($unexpected, $exception->unexpected());
        $this->assertSame($expected, $exception->expected());
        $this->assertSame($position, $exception->position());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
