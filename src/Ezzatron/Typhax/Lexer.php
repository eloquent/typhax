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
      $tokens = array_merge($tokens, $this->normalizeToken($token));
    }

    $tokens = $this->collapseQuotedStrings($tokens);
    $tokens = $this->collapseConsecutiveStrings($tokens);

    return $tokens;
  }

  /**
   * @param string|array $token
   *
   * @return array
   */
  protected function normalizeToken($token)
  {
    $token = Token::fromToken($token);

    // convert to string if not supported
    if (!$token->supported())
    {
      $token = new Token(Token::TOKEN_STRING, $token->content());
    }

    // check for custom tokens
    $tokenContentLowercase = strtolower($token->content());
    $customTokens = $this->customTokens();
    if (array_key_exists($tokenContentLowercase, $customTokens))
    {
      $token = new Token($customTokens[$tokenContentLowercase], $token->content());
    }

    // split unsupported PHP tokens that contain supported Typhax tokens
    if (Token::TOKEN_STRING === $token->type())
    {
      $candidates = array(
        Token::TOKEN_AND,
        Token::TOKEN_SQUARE_BRACKET_CLOSE,
        Token::TOKEN_SQUARE_BRACKET_OPEN,
        Token::TOKEN_COLON,
        Token::TOKEN_PARENTHESIS_CLOSE,
        Token::TOKEN_PARENTHESIS_OPEN,
        Token::TOKEN_BRACE_CLOSE,
        Token::TOKEN_BRACE_OPEN,
        Token::TOKEN_PIPE,
        Token::TOKEN_COMMA,
        Token::TOKEN_GREATER_THAN,
        Token::TOKEN_LESS_THAN,
      );

      foreach ($candidates as $candidate)
      {
        $candidateLength = strlen($candidate);
        $candidatePosition = strpos($token->content(), $candidate);

        if (false !== $candidatePosition)
        {
          $normalized = array();

          if ($candidatePosition > 0)
          {
            $preSource = substr($token->content(), 0, $candidatePosition);
            $normalized = array_merge($normalized, $this->tokens($preSource));
          }

          $candidateSource = substr($token->content(), $candidatePosition, $candidateLength);
          $normalized = array_merge($normalized, $this->tokens($candidateSource));

          if ($candidatePosition < strlen($token->content()) + $candidateLength - 2)
          {
            $postSource = substr($token->content(), $candidatePosition + $candidateLength);
            $normalized = array_merge($normalized, $this->tokens($postSource));
          }

          return $normalized;
        }
      }
    }

    return array($token);
  }

  /**
   * @param array $tokens
   *
   * @return array
   */
  protected function collapseQuotedStrings(array $tokens)
  {
    $concatenated = array();
    $numTokens = 0;
    $inQuotes = false;

    foreach ($tokens as $token)
    {
      if ($inQuotes)
      {
        $concatenated[$numTokens - 1]->append($token->content());

        if ('"' === $token->content())
        {
          $inQuotes = false;
        }

        continue;
      }
      elseif ('"' == $token->content())
      {
        $inQuotes = true;
        $concatenated[] = new Token(Token::TOKEN_STRING_QUOTED, $token->content());
        $numTokens ++;

        continue;
      }

      $concatenated[] = $token;
      $numTokens ++;
    }

    return $concatenated;
  }

  /**
   * @param array $tokens
   *
   * @return array
   */
  protected function collapseConsecutiveStrings(array $tokens)
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

  /**
   * @return array
   */
  protected function customTokens()
  {
    return array(
      'true' => Token::TOKEN_BOOLEAN_TRUE,
      'false' => Token::TOKEN_BOOLEAN_FALSE,
      'null' => Token::TOKEN_NULL,
    );
  }
}
