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
 * Represents a source token.
 */
class TypeToken
{
    /**
     * Create a token object from its PHP token representation.
     *
     * @param array|string $token The PHP token.
     *
     * @return Token The token object.
     */
    public static function fromToken($token)
    {
        if (is_string($token)) {
            return new static($token, $token);
        }

        return new static($token[0], $token[1]);
    }

    /**
     * Get the token name from a token type.
     *
     * @param integer|string $type The type.
     *
     * @return string|null The name, or null if the type is unknown.
     */
    public static function nameByType($type)
    {
        switch ($type) {
            case self::TOKEN_BOOLEAN_FALSE: return 'BOOLEAN_FALSE';
            case self::TOKEN_BOOLEAN_TRUE: return 'BOOLEAN_TRUE';
            case self::TOKEN_BRACE_CLOSE: return 'BRACE_CLOSE';
            case self::TOKEN_BRACE_OPEN: return 'BRACE_OPEN';
            case self::TOKEN_COLON: return 'COLON';
            case self::TOKEN_COMMA: return 'COMMA';
            case self::TOKEN_FLOAT: return 'FLOAT';
            case self::TOKEN_GREATER_THAN: return 'GREATER_THAN';
            case self::TOKEN_INTEGER: return 'INTEGER';
            case self::TOKEN_LESS_THAN: return 'LESS_THAN';
            case self::TOKEN_NULL: return 'NULL';
            case self::TOKEN_PIPE: return 'PIPE';
            case self::TOKEN_PLUS: return 'PLUS';
            case self::TOKEN_SQUARE_BRACKET_CLOSE:
                return 'SQUARE_BRACKET_CLOSE';
            case self::TOKEN_SQUARE_BRACKET_OPEN: return 'SQUARE_BRACKET_OPEN';
            case self::TOKEN_STRING: return 'STRING';
            case self::TOKEN_STRING_QUOTED: return 'STRING_QUOTED';
            case self::TOKEN_TYPE_NAME: return 'TYPE_NAME';
            case self::TOKEN_WHITESPACE: return 'WHITESPACE';
        }

        return null;
    }

    /**
     * Get token names from an array of token types.
     *
     * @param array<integer|string> $types The types.
     *
     * @return array<string|null> An array of names, or nulls if a type is unknown.
     */
    public static function namesByType(array $types)
    {
        $names = array();

        foreach ($types as $type) {
            $names[] = self::nameByType($type);
        }

        return $names;
    }

    /**
     * Construct a new type token.
     *
     * @param integer|string $type    The type.
     * @param string         $content The content.
     */
    public function __construct($type, $content)
    {
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Get the type.
     *
     * @return integer|string The type.
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Append to the content.
     *
     * @param string $content The content to append.
     */
    public function append($content)
    {
        $this->content .= $content;
    }

    /**
     * Get the content.
     *
     * @return string The content.
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Get the name.
     *
     * @return string|null The name.
     */
    public function name()
    {
        return self::nameByType($this->type);
    }

    /**
     * Returns true if this token is supported.
     *
     * @return boolean True if supported.
     */
    public function isSupported()
    {
        return null !== $this->name();
    }

    const TOKEN_BOOLEAN_FALSE = 'false';
    const TOKEN_BOOLEAN_TRUE = 'true';
    const TOKEN_BRACE_CLOSE = '}';
    const TOKEN_BRACE_OPEN = '{';
    const TOKEN_COLON = ':';
    const TOKEN_COMMA = ',';
    const TOKEN_FLOAT = T_DNUMBER;
    const TOKEN_GREATER_THAN = '>';
    const TOKEN_INTEGER = T_LNUMBER;
    const TOKEN_LESS_THAN = '<';
    const TOKEN_NULL = 'null';
    const TOKEN_PIPE = '|';
    const TOKEN_PLUS = '+';
    const TOKEN_SQUARE_BRACKET_CLOSE = ']';
    const TOKEN_SQUARE_BRACKET_OPEN = '[';
    const TOKEN_STRING = T_STRING;
    const TOKEN_STRING_QUOTED = T_CONSTANT_ENCAPSED_STRING;
    const TOKEN_TYPE_NAME = 'typeName';
    const TOKEN_WHITESPACE = T_WHITESPACE;

    private static $types;
    private $type;
    private $content;
}
