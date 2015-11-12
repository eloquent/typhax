<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Typhax\Type\TraversableType
 */
class TraversableTypeTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->primaryType = Phony::mock(__NAMESPACE__ . '\TraversablePrimaryType')->mock();
        $this->keyType = Phony::mock(__NAMESPACE__ . '\Type')->mock();
        $this->valueType = Phony::mock(__NAMESPACE__ . '\Type')->mock();

        $this->subject = new TraversableType($this->primaryType, $this->keyType, $this->valueType);
    }

    public function testPrimaryType()
    {
        $this->assertSame($this->primaryType, $this->subject->primaryType());
    }

    public function testKeyType()
    {
        $this->assertSame($this->keyType, $this->subject->keyType());
    }

    public function testNullKeyType()
    {
        $this->subject = new TraversableType($this->primaryType, null, $this->valueType);

        $this->assertNull($this->subject->keyType());
    }

    public function testValueType()
    {
        $this->assertSame($this->valueType, $this->subject->valueType());
    }
}
