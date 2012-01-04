<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhax;

class Type
{
  /**
   * @param string $name
   */
  public function __construct($name)
  {
    if (!is_string($name))
    {
      throw new \InvalidArgumentException('Type name must be a string.');
    }

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
    if (!is_string($name))
    {
      throw new \InvalidArgumentException('Attribute name must be a string.');
    }

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
   * @param Type $type
   */
  public function addSubType(self $type)
  {
    $this->subTypes[] = $type;
  }

  /**
   * @return array<integer,Ezzatron\Typhax\Type>
   */
  public function subTypes()
  {
    return $this->subTypes;
  }

  /**
   * @var string
   */
  protected $name;

  /**
   * @var array<string,mixed>
   */
  protected $attributes = array();

  /**
   * @var array<integer,Ezzatron\Typhax\Type>
   */
  protected $subTypes = array();
}
