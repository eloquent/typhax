<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\IntrinsicType;

class IntrinsicTypeNameTest extends \PHPUnit_Framework_TestCase
{
    public function testEnumeration()
    {
        $expected = array(
            'NAME_ARRAY' => IntrinsicTypeName::NAME_ARRAY(),
            'NAME_BOOLEAN' => IntrinsicTypeName::NAME_BOOLEAN(),
            'NAME_CALLBACK' => IntrinsicTypeName::NAME_CALLBACK(),
            'NAME_CALLBACK_WRAPPER' => IntrinsicTypeName::NAME_CALLBACK_WRAPPER(),
            'NAME_CHARACTER' => IntrinsicTypeName::NAME_CHARACTER(),
            'NAME_CLASS_NAME' => IntrinsicTypeName::NAME_CLASS_NAME(),
            'NAME_DIRECTORY' => IntrinsicTypeName::NAME_DIRECTORY(),
            'NAME_FILE' => IntrinsicTypeName::NAME_FILE(),
            'NAME_FILTER' => IntrinsicTypeName::NAME_FILTER(),
            'NAME_FLOAT' => IntrinsicTypeName::NAME_FLOAT(),
            'NAME_INTEGER' => IntrinsicTypeName::NAME_INTEGER(),
            'NAME_INTEGERABLE' => IntrinsicTypeName::NAME_INTEGERABLE(),
            'NAME_INTERFACE_NAME' => IntrinsicTypeName::NAME_INTERFACE_NAME(),
            'NAME_KEY' => IntrinsicTypeName::NAME_KEY(),
            'NAME_MIXED' => IntrinsicTypeName::NAME_MIXED(),
            'NAME_NULL' => IntrinsicTypeName::NAME_NULL(),
            'NAME_NUMBER' => IntrinsicTypeName::NAME_NUMBER(),
            'NAME_NUMERIC' => IntrinsicTypeName::NAME_NUMERIC(),
            'NAME_OBJECT' => IntrinsicTypeName::NAME_OBJECT(),
            'NAME_RESOURCE' => IntrinsicTypeName::NAME_RESOURCE(),
            'NAME_SCALAR' => IntrinsicTypeName::NAME_SCALAR(),
            'NAME_SOCKET' => IntrinsicTypeName::NAME_SOCKET(),
            'NAME_STREAM' => IntrinsicTypeName::NAME_STREAM(),
            'NAME_STRING' => IntrinsicTypeName::NAME_STRING(),
            'NAME_STRINGABLE' => IntrinsicTypeName::NAME_STRINGABLE(),
            'NAME_TRAVERSABLE' => IntrinsicTypeName::NAME_TRAVERSABLE(),
            'NAME_TUPLE' => IntrinsicTypeName::NAME_TUPLE(),
            'NAME_TYPE_NAME' => IntrinsicTypeName::NAME_TYPE_NAME(),
        );

        $this->assertSame($expected, IntrinsicTypeName::multitonInstances());
    }

    public function valueData()
    {
        return array(
            array('NAME_ARRAY', 'array'),
            array('NAME_BOOLEAN', 'boolean'),
            array('NAME_CALLBACK', 'callback'),
            array('NAME_CALLBACK_WRAPPER', 'callback_wrapper'),
            array('NAME_CHARACTER', 'character'),
            array('NAME_CLASS_NAME', 'class_name'),
            array('NAME_DIRECTORY', 'directory'),
            array('NAME_FILE', 'file'),
            array('NAME_FILTER', 'filter'),
            array('NAME_FLOAT', 'float'),
            array('NAME_INTEGER', 'integer'),
            array('NAME_INTEGERABLE', 'integerable'),
            array('NAME_INTERFACE_NAME', 'interface_name'),
            array('NAME_KEY', 'key'),
            array('NAME_MIXED', 'mixed'),
            array('NAME_NULL', 'null'),
            array('NAME_NUMBER', 'number'),
            array('NAME_NUMERIC', 'numeric'),
            array('NAME_OBJECT', 'object'),
            array('NAME_RESOURCE', 'resource'),
            array('NAME_SCALAR', 'scalar'),
            array('NAME_SOCKET', 'socket'),
            array('NAME_STREAM', 'stream'),
            array('NAME_STRING', 'string'),
            array('NAME_STRINGABLE', 'stringable'),
            array('NAME_TRAVERSABLE', 'traversable'),
            array('NAME_TUPLE', 'tuple'),
            array('NAME_TYPE_NAME', 'type_name'),
        );
    }

    /**
     * @dataProvider valueData
     */
    public function testValues($key, $value)
    {
        $this->assertSame($value, IntrinsicTypeName::instanceByKey($key)->value());
    }
}
