<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax;

use Eloquent\Typhax\Comparator\TypeEquivalenceComparator;
use Eloquent\Typhax\Parser\TypeParser;
use Eloquent\Typhax\Renderer\CondensedTypeRenderer;
use PHPUnit_Framework_TestCase;

/**
 * @coversNothing
 */
class DocumentationTest extends PHPUnit_Framework_TestCase
{
    public function testParserUsage()
    {
        $parser = TypeParser::create();
        $type = $parser->parse('primaryType<keyType,valueType>');

        $renderer = CondensedTypeRenderer::create();
        echo $renderer->render($type); // outputs 'primaryType<keyType,valueType>'

        $this->expectOutputString('primaryType<keyType,valueType>');
        $this->assertInstanceOf('Eloquent\Typhax\Type\Type', $type);
    }

    public function testComparatorUsage()
    {
        $parser = TypeParser::create();

        $typeA = $parser->parse('integer|string');
        $typeB = $parser->parse('string|integer');
        $typeC = $parser->parse('string|integer|null');

        $comparator = TypeEquivalenceComparator::create();
        var_dump($comparator->isEquivalent($typeA, $typeB)); // outputs 'bool(true)'
        var_dump($comparator->isEquivalent($typeB, $typeC)); // outputs 'bool(false)'

        $this->expectOutputString("bool(true)\nbool(false)\n");
    }
}
