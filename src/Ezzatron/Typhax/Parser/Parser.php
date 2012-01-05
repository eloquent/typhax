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

    $this->compositePrecedence = array(
      Token::TOKEN_AND,
      Token::TOKEN_PIPE,
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
    
    $root = $this->typeOrComposite();
    $this->assert(Token::TOKEN_END);

    return $root;
  }

  /**
   * @return Type|Composite
   */
  protected function typeOrComposite()
  {
    $types = array();
    $separators = array();

    while (true)
    {
      $types[] = $this->type();

      $token = $this->assert(array(
        Token::TOKEN_PIPE,
        Token::TOKEN_AND,
        Token::TOKEN_END,
      ));
      if (Token::TOKEN_END === $token->type())
      {
        break;
      }
      next($this->tokens);
      
      $separators[] = $token->type();
    }

    if (1 === count($types))
    {
      return array_pop($types);
    }

    return $this->resolveComposite($types, $separators);
  }

  /**
   * @param array<Type> $types
   * @param array<integer|string> $separators
   *
   * @return Composite
   */
  protected function resolveComposite(array $types, array $separators)
  {
    foreach ($this->compositePrecedence as $currentSeparator)
    {
//      echo 'Starting separator '.$currentSeparator.PHP_EOL;

      $numTypes = count($types);
      $newTypes = array();
      $newSeparators = array();
      $composite = null;

      for ($i = 0; $i < $numTypes; $i ++)
      {
        $type = current($types);
        next($types);
        $separator = current($separators);
        next($separators);

//        echo 'This separator is '.var_export($separator, true).PHP_EOL;

        if (
          !$composite
          && $separator === $currentSeparator
        )
        {
//          echo 'Starting a new composite of separator '.$currentSeparator.PHP_EOL;

          $composite = new Composite($currentSeparator);
        }

        if ($composite)
        {
//          echo 'Adding '.get_class($type).' to composite'.PHP_EOL;

          $composite->addType($type);
        }
        else
        {
//          echo 'Adding '.get_class($type).' to new types'.PHP_EOL;

          $newTypes[] = $type;
          $newSeparators[] = $separator;
        }

        if (
          $composite
          && $separator !== $currentSeparator
        )
        {
//          echo 'Closing composite of type '.get_class($composite).' and adding to new types'.PHP_EOL;

          $newTypes[] = $composite;
          $newSeparators[] = $separator;
          $composite = null;
        }
      }

//      echo 'Setting new types'.PHP_EOL;

      $types = $newTypes;
      $separators = $newSeparators;
    }

//    echo 'Done'.PHP_EOL;
//    ob_flush();

    $composite = array_pop($types);

    return $composite;
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
   * @var array<integer|string>
   */
  protected $compositePrecedence;

  /**
   * @var array<integer,Token>
   */
  protected $tokens;
}
