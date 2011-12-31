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
        $tokens[] = Token::fromArray($token);
      }
      else
      {
        $tokens[] = Token::fromCharacter($token);
      }
    }

    return $tokens;
  }
}