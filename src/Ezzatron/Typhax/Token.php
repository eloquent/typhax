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

class Token
{
  /**
   * @param array $token
   *
   * @return Token
   */
  static public function fromArray(array $token)
  {
    return new self($token[0], $token[1]);
  }

  /**
   * @param string $token
   *
   * @return Token
   */
  static public function fromCharacter($token)
  {
    return new self($token, $token);
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

  const TOKEN_AND = '&';
  const TOKEN_OR = '|';
  const TOKEN_SEPARATOR = ',';
  const TOKEN_STRING = T_STRING;
  const TOKEN_SUBTYPE_CLOSE = '>';
  const TOKEN_SUBTYPE_START = '<';
  const TOKEN_WHITESPACE = T_WHITESPACE;

  /**
   * @var integer|string
   */
  protected $type;

  /**
   * @var string
   */
  protected $content;
}