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
      if (is_array($token))
      {
        $token = Token::fromArray($token);
      }
      else
      {
        $token = Token::fromCharacter($token);
      }

      if (!$token->supported())
      {
        $token = new Token(Token::TOKEN_STRING, $token->content());
      }

      if (Token::TOKEN_STRING === $token->type())
      {
        $numTokens = count($tokens);
        if (array_key_exists($numTokens - 1, $tokens))
        {
          $previousToken = $tokens[$numTokens - 1];

          if (Token::TOKEN_STRING === $previousToken->type())
          {
            $previousToken->append($token->content());

            continue;
          }
        }
      }

      $tokens[] = $token;
    }

    return $tokens;
  }
}