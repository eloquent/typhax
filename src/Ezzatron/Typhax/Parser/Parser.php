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
use Ezzatron\Typhax\AST\Node;
use Ezzatron\Typhax\AST\Type;
use Ezzatron\Typhax\Lexer\Lexer;
use Ezzatron\Typhax\Lexer\Token;

class Parser
{
  public function __construct(Lexer $lexer = null)
  {
    if (null === $lexer)
    {
      $lexer = new Lexer;
    }
    $this->lexer = $lexer;

    $this->compositePrecedence = array(
      Token::TOKEN_PIPE,
      Token::TOKEN_AND,
    );
  }

  /**
   * @return Lexer
   */
  public function lexer()
  {
    return $this->lexer;
  }

  /**
   * @param string $source
   *
   * @return Node
   */
  public function parse($source)
  {
    $this->tokens = $this->lexer->tokens($source);

    $node = $this->parseType();

    if (Token::TOKEN_END !== current($this->tokens)->type()) {
      $node = $this->parseComposite($node);
    }

    $this->assert(Token::TOKEN_END);

    return $node;
  }

  /**
   * @return Type
   */
  protected function parseType()
  {
    $type = new Type(
      $this->assert(Token::TOKEN_STRING)->content()
    );

    $token = next($this->tokens);

    return $type;
  }

  /**
   * @param Node $types
   * @param integer $minimum_precedence
   *
   * @return Node
   */
  protected function parseComposite(Node $left, $minimum_precedence = 0)
  {
    while ($minimum_precedence <= ($precedence = $this->getPrecedence()))
    {
      $operator = current($this->tokens)->content();

      next($this->tokens);

      $right = $this->parseType();

      if ($precedence < $this->getPrecedence())
      {
        $right = $this->parseComposite($right, $precedence + 1);
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
   * @return integer
   */
  protected function getPrecedence() {
    $token = current($this->tokens);
    if ($token)
    {
      $precedence = array_search(
        current($this->tokens)->type()
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
  protected $compositePrecedence;

  /**
   * @var array<integer,Token>
   */
  protected $tokens;
}
