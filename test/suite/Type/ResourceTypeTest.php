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

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Typhax\Type\ResourceType
 */
class ResourceTypeTest extends PHPUnit_Framework_TestCase
{
    public function testResourceType()
    {
        $type = new ResourceType();

        $this->assertNull($type->ofType());

        $type = new ResourceType('foo');

        $this->assertSame('foo', $type->ofType());
    }
}
