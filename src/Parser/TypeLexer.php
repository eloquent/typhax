<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser;

/**
 * Lexer for Typhax type expressions.
 */
class TypeLexer
{
    /**
     * Produce tokens from the provided source.
     *
     * @param string  $source The source.
     * @param integer $offset The offset to start lexing at.
     *
     * @return array<TypeToken> The tokens.
     */
    public function tokens($source, $offset)
    {
        $tokens = array();

        $rawTokens = token_get_all('<?php ' . substr($source, $offset));
        array_shift($rawTokens);

        foreach ($rawTokens as $token) {
            $tokens = array_merge($tokens, $this->normalizeToken($token));
        }

        $tokens = $this->collapseQuotedStrings($tokens);
        $tokens = $this->collapseConsecutiveStrings($tokens);

        return $tokens;
    }

    private function normalizeToken($token)
    {
        $token = TypeToken::fromToken($token);

        // convert to string if not supported
        if (!$token->isSupported()) {
            $token = new TypeToken(TypeToken::TOKEN_STRING, $token->content());
        }

        // check for custom tokens
        $tokenContentLowercase = strtolower($token->content());

        if (array_key_exists($tokenContentLowercase, $this->customTokens)) {
            $token = new TypeToken(
                $this->customTokens[$tokenContentLowercase],
                $token->content()
            );
        }

        // split unsupported PHP tokens that contain supported Typhax tokens
        if (TypeToken::TOKEN_STRING === $token->type()) {
            $candidates = array(
                TypeToken::TOKEN_SQUARE_BRACKET_CLOSE,
                TypeToken::TOKEN_SQUARE_BRACKET_OPEN,
                TypeToken::TOKEN_COLON,
                TypeToken::TOKEN_BRACE_CLOSE,
                TypeToken::TOKEN_BRACE_OPEN,
                TypeToken::TOKEN_PIPE,
                TypeToken::TOKEN_PLUS,
                TypeToken::TOKEN_COMMA,
                TypeToken::TOKEN_GREATER_THAN,
                TypeToken::TOKEN_LESS_THAN,
            );

            foreach ($candidates as $candidate) {
                $candidateLength = strlen($candidate);
                $candidateOffset = strpos($token->content(), $candidate);

                if (false !== $candidateOffset) {
                    $normalized = array();

                    if ($candidateOffset > 0) {
                        $preSource =
                            substr($token->content(), 0, $candidateOffset);
                        $normalized = array_merge(
                            $normalized,
                            $this->tokens($preSource, 0)
                        );
                    }

                    $candidateSource = substr(
                        $token->content(),
                        $candidateOffset,
                        $candidateLength
                    );
                    $normalized = array_merge(
                        $normalized,
                        $this->tokens($candidateSource, 0)
                    );

                    if (
                        $candidateOffset <
                            strlen($token->content()) + $candidateLength - 2
                    ) {
                        $postSource = substr(
                            $token->content(),
                            $candidateOffset + $candidateLength
                        );
                        $normalized = array_merge(
                            $normalized,
                            $this->tokens($postSource, 0)
                        );
                    }

                    return $normalized;
                }
            }
        }

        return array($token);
    }

    private function collapseQuotedStrings(array $tokens)
    {
        $collapsed = array();
        $numTokens = 0;
        $inQuotes = false;

        foreach ($tokens as $token) {
            if ($inQuotes) {
                $collapsed[$numTokens - 1]->append($token->content());

                if ('"' === $token->content()) {
                    $inQuotes = false;
                }

                continue;
            } elseif ('"' == $token->content()) {
                $inQuotes = true;
                $collapsed[] = new TypeToken(
                    TypeToken::TOKEN_STRING_QUOTED,
                    $token->content()
                );
                ++$numTokens;

                continue;
            }

            $collapsed[] = $token;
            ++$numTokens;
        }

        return $collapsed;
    }

    private function collapseConsecutiveStrings(array $tokens)
    {
        $collapsed = array();
        $numTokens = 0;

        foreach ($tokens as $token) {
            if (
                TypeToken::TOKEN_STRING === $token->type() &&
                array_key_exists($numTokens - 1, $collapsed) &&
                TypeToken::TOKEN_STRING === $collapsed[$numTokens - 1]->type()
            ) {
                $collapsed[$numTokens - 1]->append($token->content());

                continue;
            }

            $collapsed[] = $token;
            ++$numTokens;
        }

        return $collapsed;
    }

    private $customTokens = array(
        'true' => TypeToken::TOKEN_BOOLEAN_TRUE,
        'false' => TypeToken::TOKEN_BOOLEAN_FALSE,
        'null' => TypeToken::TOKEN_NULL,

        'array' => TypeToken::TOKEN_TYPE_NAME,
        'boolean' => TypeToken::TOKEN_TYPE_NAME,
        'callable' => TypeToken::TOKEN_TYPE_NAME,
        'float' => TypeToken::TOKEN_TYPE_NAME,
        'integer' => TypeToken::TOKEN_TYPE_NAME,
        'mixed' => TypeToken::TOKEN_TYPE_NAME,
        'object' => TypeToken::TOKEN_TYPE_NAME,
        'resource' => TypeToken::TOKEN_TYPE_NAME,
        'stream' => TypeToken::TOKEN_TYPE_NAME,
        'string' => TypeToken::TOKEN_TYPE_NAME,
        'stringable' => TypeToken::TOKEN_TYPE_NAME,
        'tuple' => TypeToken::TOKEN_TYPE_NAME,

        'bool' => TypeToken::TOKEN_TYPE_NAME,
        'callback' => TypeToken::TOKEN_TYPE_NAME,
        'double' => TypeToken::TOKEN_TYPE_NAME,
        'int' => TypeToken::TOKEN_TYPE_NAME,
        'long' => TypeToken::TOKEN_TYPE_NAME,
        'number' => TypeToken::TOKEN_TYPE_NAME,
        'numeric' => TypeToken::TOKEN_TYPE_NAME,
        'real' => TypeToken::TOKEN_TYPE_NAME,
        'scalar' => TypeToken::TOKEN_TYPE_NAME,
    );
}
