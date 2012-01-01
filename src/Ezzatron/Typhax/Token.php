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

use ReflectionClass;

class Token
{
  /**
   * @param array|string $token
   *
   * @return Token
   */
  static public function fromToken($token)
  {
    if (is_string($token))
    {
      return static::fromCharacter($token);
    }

    return static::fromArray($token);
  }

  /**
   * @param array $token
   *
   * @return Token
   */
  static public function fromArray(array $token)
  {
    return new static($token[0], $token[1]);
  }

  /**
   * @param string $token
   *
   * @return Token
   */
  static public function fromCharacter($token)
  {
    return new static($token, $token);
  }

  /**
   * @param integer|string $type
   * @param string $content
   */
  public function __construct($type, $content)
  {
    $this->type = $type;
    $this->content = $content;
  }

  /**
   * @return integer|string
   */
  public function type()
  {
    return $this->type;
  }

  /**
   * @param string $content
   */
  public function append($content)
  {
    $this->content .= $content;
  }

  /**
   * @return string
   */
  public function content()
  {
    return $this->content;
  }

  /**
   * @return string|null
   */
  public function name()
  {
    foreach (static::types() as $name => $value)
    {
      if ($value === $this->type)
      {
        return $name;
      }
    }

    return null;
  }

  /**
   * @return boolean
   */
  public function supported()
  {
    return null !== $this->name();
  }

  /**
   * @return string
   */
  public function string()
  {
    return $this->content();
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->string();
  }

  /**
   * @return array
   */
  static protected function types()
  {
    if (null !== static::$types)
    {
      return static::$types;
    }

    static::$types = array();
    $reflector = new ReflectionClass(get_called_class());

    foreach ($reflector->getConstants() as $name => $value)
    {
      if ('TOKEN_' == substr($name, 0, 6))
      {
        static::$types[substr($name, 6)] = $value;
      }
    }

    return static::$types;
  }

  const TOKEN_AND = '&';
  const TOKEN_ARRAY_CLOSE = ']';
  const TOKEN_ARRAY_OPEN = '[';
  const TOKEN_ASSIGNMENT = ':';
  const TOKEN_ATTRIBUTES_CLOSE = ')';
  const TOKEN_ATTRIBUTES_OPEN = '(';
  const TOKEN_FLOAT = T_DNUMBER;
  const TOKEN_HASH_CLOSE = '}';
  const TOKEN_HASH_OPEN = '{';
  const TOKEN_INTEGER = T_LNUMBER;
  const TOKEN_KEYWORD = 'keyword';
  const TOKEN_OR = '|';
  const TOKEN_SEPARATOR = ',';
  const TOKEN_STRING = T_STRING;
  const TOKEN_STRING_QUOTED = T_CONSTANT_ENCAPSED_STRING;
  const TOKEN_SUBTYPE_CLOSE = '>';
  const TOKEN_SUBTYPE_OPEN = '<';
  const TOKEN_WHITESPACE = T_WHITESPACE;

  /**
   * @var array
   */
  static protected $types;

  /**
   * @var integer|string
   */
  protected $type;

  /**
   * @var string
   */
  protected $content;
}
