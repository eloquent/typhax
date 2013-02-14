<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Type;

use PHPUnit_Framework_TestCase;

class StreamTypeTest extends PHPUnit_Framework_TestCase
{
    public function testStreamType()
    {
        $type = new StreamType;

        $this->assertNull($type->readable());
        $this->assertNull($type->writable());

        $type = new StreamType(true, false);

        $this->assertTrue($type->readable());
        $this->assertFalse($type->writable());

        $type = new StreamType(false, true);

        $this->assertFalse($type->readable());
        $this->assertTrue($type->writable());
    }
}
