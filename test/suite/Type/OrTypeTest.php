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
 * @covers \Eloquent\Typhax\Type\OrType
 */
class OrTypeTest extends PHPUnit_Framework_TestCase
{
    public function testTypes()
    {
        $types = array(
            Phony::mock(__NAMESPACE__ . '\Type')->mock(),
            Phony::mock(__NAMESPACE__ . '\Type')->mock(),
        );
        $type = new OrType($types);

        $this->assertSame($types, $type->types());
    }
}
