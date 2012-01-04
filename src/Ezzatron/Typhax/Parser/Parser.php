<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhax\Parser;

use Ezzatron\Typhax\AST\Composite;
use Ezzatron\Typhax\AST\Type;
use Ezzatron\Typhax\Lexer\Lexer;
use Ezzatron\Typhax\Lexer\Token;

class Parser
{
  public function __construct()
  {
    $this->lexer = new Lexer;

    $this->typeTerminators = array(
      Token::TOKEN_PIPE,
      Token::TOKEN_AND,
      Token::TOKEN_END,
    );
  }

  /**
   * @param string $source
   * 
   * @return Type
   */
  public function parse($source)
  {
    $this->tokens = $this->lexer->tokens($source);
    
    $type = $this->type();
    $this->assert(Token::TOKEN_END);

    return $type;
  }

  /**
   * @return Type
   */
  protected function type()
  {
    $type = new Type(
      $this->assert(Token::TOKEN_STRING)->content()
    );
    next($this->tokens);

    $token = $this->assert(array(
      Token::TOKEN_PARENTHESIS_OPEN,
      Token::TOKEN_LESS_THAN,
      Token::TOKEN_PIPE,
      Token::TOKEN_AND,
      Token::TOKEN_END,
    ));
 
    if (in_array($token->type(), $this->typeTerminators, true))
    {
      return $type;
    }

    return $type;
  }

  /**
   * @param integer|string|array<integer|string> $types
   *
   * @return Token|null
   */
  protected function assert($types)
  {
    if (!is_array($types))
    {
      $types = array($types);
    }

    $token = current($this->tokens);

    if (!in_array($token->type(), $types, true))
    {
      throw new Exception\UnexpectedTokenException(
        $token->name()
        , $this->position()
        , $this->tokenNames($types)
      );
    }

    return $token;
  }

  /**
   * @return integer
   */
  protected function position()
  {
    $index = key($this->tokens);

    $source = '';
    for ($i = 0; $i <= $index; $i ++) {
      $source .= $this->tokens[$i]->content();
    }

    return mb_strlen($source);
  }

  /**
   * @param array<integer|string> $types
   *
   * @return array<string>
   */
  protected function tokenNames(array $types)
  {
    $names = array();
    foreach ($types as $type)
    {
      $names[] = Token::nameByType($type);
    }

    return $names;
  }

  /**
   * @var Lexer
   */
  protected $lexer;

  /**
   * @var array<integer|string>
   */
  protected $typeTerminators;

  /**
   * @var array<integer,Token>
   */
  protected $tokens;
}
