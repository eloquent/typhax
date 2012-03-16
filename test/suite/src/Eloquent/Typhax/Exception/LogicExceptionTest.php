<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Exception;

use Phake;

class LogicExceptionTest extends \Eloquent\Typhax\Test\TestCase
{
  /**
   * @covers Eloquent\Typhax\Exception\LogicException
   * @covers Eloquent\Typhax\Exception\Exception
   * @group exceptions
   * @group core
   */
  public function testException()
  {
    $message = 'foo';
    $previous = new \Exception;
    $exception = Phake::partialMock(__NAMESPACE__.'\LogicException', $message, $previous);

    $this->assertSame($message, $exception->getMessage());
    $this->assertSame(0, $exception->getCode());
    $this->assertSame($previous, $exception->getPrevious());
  }
}
