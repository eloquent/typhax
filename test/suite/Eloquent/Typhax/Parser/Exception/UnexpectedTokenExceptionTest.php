<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser\Exception;

class UnexpectedTokenTest extends \PHPUnit_Framework_TestCase
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
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertSame($position, $exception->position());

        $unexpected = 'foo';
        $position = 666;
        $expected = array('bar', 'baz');
        $exception = new UnexpectedTokenException($unexpected, $position, $expected);

        $expectedMessage = 'Unexpected foo at position 666. Expected one of bar, baz.';

        $this->assertSame($expectedMessage, $exception->getMessage());
    }
}
