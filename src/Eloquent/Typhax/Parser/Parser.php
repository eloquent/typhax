<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser;

use Eloquent\Typhax\AST\Composite;
use Eloquent\Typhax\AST\Node;
use Eloquent\Typhax\AST\Type;
use Eloquent\Typhax\Lexer\Token;

class Parser
{
  public function __construct()
  {
    $this->compositePrecedence = array(
      Token::TOKEN_PIPE,
      Token::TOKEN_AND,
    );
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return Node
   */
  public function parse(array &$tokens)
  {
    $node = $this->parseType($tokens);

    if (Token::TOKEN_END !== current($tokens)->type())
    {
      $node = $this->parseComposite($tokens, $node);
    }

    $this->assert($tokens, Token::TOKEN_END);

    return $node;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return Type
   */
  protected function parseType(array &$tokens)
  {
    $type = new Type(
      $this->assert($tokens, Token::TOKEN_STRING)->content()
    );

    $token = next($tokens);

    if (Token::TOKEN_LESS_THAN === current($tokens)->type())
    {
      foreach ($this->parseSubTypes($tokens) as $subType)
      {
        $type->addSubType($subType);
      }
    }

    if (Token::TOKEN_PARENTHESIS_OPEN === current($tokens)->type())
    {
      foreach ($this->parseAttributes($tokens) as $name => $value)
      {
        $type->setAttribute($name, $value);
      }
    }

    return $type;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return array
   */
  protected function parseAttributes(array &$tokens)
  {
    $this->assert($tokens, Token::TOKEN_PARENTHESIS_OPEN);
    next($tokens);

    if (Token::TOKEN_PARENTHESIS_CLOSE === current($tokens)->type())
    {
      next($tokens);

      return array();
    }

    $attributes = $this->parseHashContents($tokens);

    $this->assert($tokens, Token::TOKEN_PARENTHESIS_CLOSE);
    next($tokens);

    return $attributes;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return array<Node>
   */
  protected function parseSubTypes(array &$tokens)
  {
    $this->assert($tokens, Token::TOKEN_LESS_THAN);
    next($tokens);

    if (Token::TOKEN_GREATER_THAN === current($tokens)->type())
    {
      next($tokens);

      return array();
    }

    $types = array();
    while (true)
    {
      $types[] = $this->parseType($tokens);

      if (Token::TOKEN_COMMA !== current($tokens)->type())
      {
        break;
      }
      next($tokens);
    }

    $this->assert($tokens, Token::TOKEN_GREATER_THAN);
    next($tokens);

    return $types;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return mixed
   */
  protected function parseValue(array &$tokens)
  {
    $token = $this->assert($tokens, array(
      Token::TOKEN_STRING,
      Token::TOKEN_STRING_QUOTED,
      Token::TOKEN_INTEGER,
      Token::TOKEN_FLOAT,
      Token::TOKEN_NULL,
      Token::TOKEN_BOOLEAN_TRUE,
      Token::TOKEN_BOOLEAN_FALSE,
      Token::TOKEN_BRACE_OPEN,
      Token::TOKEN_SQUARE_BRACKET_OPEN,
    ));

    if (Token::TOKEN_BRACE_OPEN === $token->type())
    {
      return $this->parseHash($tokens);
    }

    if (Token::TOKEN_SQUARE_BRACKET_OPEN === $token->type())
    {
      return $this->parseArray($tokens);
    }

    next($tokens);

    switch ($token->type())
    {
      case Token::TOKEN_STRING_QUOTED:
        return substr($token->content(), 1, -1);
      case Token::TOKEN_INTEGER:
        return intval($token->content());
      case Token::TOKEN_FLOAT:
        return floatval($token->content());
      case Token::TOKEN_NULL:
        return null;
      case Token::TOKEN_BOOLEAN_TRUE:
        return true;
      case Token::TOKEN_BOOLEAN_FALSE:
        return false;
    }

    return $token->content();
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return array
   */
  protected function parseHash(array &$tokens)
  {
    $this->assert($tokens, Token::TOKEN_BRACE_OPEN);
    next($tokens);

    if (Token::TOKEN_BRACE_CLOSE === current($tokens)->type())
    {
      next($tokens);

      return array();
    }

    $hash = $this->parseHashContents($tokens);

    $this->assert($tokens, Token::TOKEN_BRACE_CLOSE);
    next($tokens);

    return $hash;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return array
   */
  protected function parseHashContents(array &$tokens)
  {
    $hash = array();
    while (true)
    {
      $key = $this->parseValue($tokens);

      $this->assert($tokens, Token::TOKEN_COLON);
      next($tokens);

      $hash[$key] = $this->parseValue($tokens);

      if (Token::TOKEN_COMMA !== current($tokens)->type())
      {
        break;
      }
      next($tokens);
    }

    return $hash;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return array
   */
  protected function parseArray(array &$tokens)
  {
    $this->assert($tokens, Token::TOKEN_SQUARE_BRACKET_OPEN);
    next($tokens);

    if (Token::TOKEN_SQUARE_BRACKET_CLOSE === current($tokens)->type())
    {
      next($tokens);

      return array();
    }

    $array = array();
    while (true)
    {
      $array[] = $this->parseValue($tokens);

      if (Token::TOKEN_COMMA !== current($tokens)->type())
      {
        break;
      }
      next($tokens);
    }

    $this->assert($tokens, Token::TOKEN_SQUARE_BRACKET_CLOSE);
    next($tokens);

    return $array;
  }

  /**
   * @param array<integer,Token> &$tokens
   * @param Node $types
   * @param integer $minimum_precedence
   *
   * @return Node
   */
  protected function parseComposite(array &$tokens, Node $left, $minimum_precedence = 0)
  {
    while ($minimum_precedence <= ($precedence = $this->getCompositePrecedence($tokens)))
    {
      $operator = current($tokens)->content();

      next($tokens);

      $right = $this->parseType($tokens);

      if ($precedence < $this->getCompositePrecedence($tokens))
      {
        $right = $this->parseComposite($tokens, $right, $precedence + 1);
      }

      $left = $this->makeComposite($operator, $left, $right);
    }

    return $left;
  }

  /**
   * Construct a Composite instance from left and right type expressions.
   *
   * Re-uses an existing left-hand-side composite if the operator matches, otherwise
   * a new composite AST node is created.
   *
   * @param string $operator
   * @param Node $left
   * @param Node $right
   *
   * @return Composite
   */
  protected function makeComposite($operator, Node $left, Node $right)
  {
    if ($left instanceof Composite && $left->separator() === $operator)
    {
      $left->addType($right);

      return $left;
    }

    $composite = new Composite($operator);
    $composite->addType($left);
    $composite->addType($right);

    return $composite;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return integer
   */
  protected function getCompositePrecedence(array &$tokens) {
    $token = current($tokens);
    if ($token)
    {
      $precedence = array_search(
        $token->type()
        , $this->compositePrecedence
        , true
      );

      if (false !== $precedence)
      {
        return $precedence;
      }
    }

    return -1;
  }

  /**
   * @param array<integer,Token> &$tokens
   * @param integer|string|array<integer|string> $types
   *
   * @return Token|null
   */
  protected function assert(array &$tokens, $types)
  {
    if (!is_array($types))
    {
      $types = array($types);
    }

    $token = current($tokens);

    if (!in_array($token->type(), $types, true))
    {
      throw new Exception\UnexpectedTokenException(
        $token->name()
        , $this->position($tokens)
        , $this->tokenNames($types)
      );
    }

    return $token;
  }

  /**
   * @param array<integer,Token> &$tokens
   *
   * @return integer
   */
  protected function position(array &$tokens)
  {
    $index = key($tokens);

    $source = '';
    for ($i = 0; $i <= $index; $i ++) {
      $source .= $tokens[$i]->content();
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
   * @var array<integer|string>
   */
  protected $compositePrecedence;
}
