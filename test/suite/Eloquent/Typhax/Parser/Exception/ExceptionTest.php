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

use Phake;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $message = 'foo';
        $position = 666;
        $previous = new \Exception;
        $exception = Phake::partialMock(__NAMESPACE__.'\Exception', $message, $position, $previous);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertSame($position, $exception->position());
    }
}
