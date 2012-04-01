<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\AST;

class Type implements Node
{
  /**
   * @param string $name
   */
  public function __construct($name)
  {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function name()
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @param mixed $value
   */
  public function setAttribute($name, $value)
  {
    $this->attributes[$name] = $value;
  }

  /**
   * @return array<string,mixed>
   */
  public function attributes()
  {
    return $this->attributes;
  }

  /**
   * @param Node $type
   */
  public function addSubType(Node $type)
  {
    $this->subTypes[] = $type;
  }

  /**
   * @return array<integer,Node>
   */
  public function subTypes()
  {
    return $this->subTypes;
  }

  const NAME_ARRAY = 'array';
  const NAME_BOOLEAN = 'boolean';
  const NAME_CALLBACK = 'callback';
  const NAME_CALLBACK_WRAPPER = 'callback_wrapper';
  const NAME_CHARACTER = 'character';
  const NAME_CLASS_NAME = 'class_name';
  const NAME_DIRECTORY = 'directory';
  const NAME_FILE = 'file';
  const NAME_FILTER = 'filter';
  const NAME_FLOAT = 'float';
  const NAME_INTEGER = 'integer';
  const NAME_INTEGERABLE = 'integerable';
  const NAME_INTERFACE_NAME = 'interface_name';
  const NAME_KEY = 'key';
  const NAME_MIXED = 'mixed';
  const NAME_NULL = 'null';
  const NAME_NUMBER = 'number';
  const NAME_NUMERIC = 'numeric';
  const NAME_OBJECT = 'object';
  const NAME_RESOURCE = 'resource';
  const NAME_SCALAR = 'scalar';
  const NAME_SOCKET = 'socket';
  const NAME_STREAM = 'stream';
  const NAME_STRING = 'string';
  const NAME_STRINGABLE = 'stringable';
  const NAME_TRAVERSABLE = 'traversable';
  const NAME_TYPE_NAME = 'type_name';

  /**
   * @var string
   */
  protected $name;

  /**
   * @var array<string,mixed>
   */
  protected $attributes = array();

  /**
   * @var array<integer,Node>
   */
  protected $subTypes = array();
}
