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

class Lexer
{
  /**
   * @param string $source
   *
   * @return array
   */
  public function tokens($source)
  {
    $tokens = array();

    $rawTokens = token_get_all('<?php '.$source);
    array_shift($rawTokens);
    
    foreach ($rawTokens as $token)
    {
      $tokens[] = $this->normalizeToken($token);
    }

    return $this->concatenateStrings($tokens);
  }

  /**
   * @param string|array $token
   *
   * @return Token
   */
  protected function normalizeToken($token)
  {
    $token = Token::fromToken($token);
    if (!$token->supported())
    {
      $token = new Token(Token::TOKEN_STRING, $token->content());
    }

    return $token;
  }

  /**
   * @param array $tokens
   *
   * @return array
   */
  protected function concatenateStrings(array $tokens)
  {
    $concatenated = array();
    $numTokens = 0;

    foreach ($tokens as $token)
    {
      if (
        Token::TOKEN_STRING === $token->type()
        && array_key_exists($numTokens - 1, $concatenated)
        && Token::TOKEN_STRING === $concatenated[$numTokens - 1]->type()
      )
      {
        $concatenated[$numTokens - 1]->append($token->content());

        continue;
      }

      $concatenated[] = $token;
      $numTokens ++;
    }

    return $concatenated;
  }
}