<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Lexer;

use ReflectionClass;

class Token
{
    /**
     * @param array|string $token
     *
     * @return Token
     */
    static public function fromToken($token)
    {
        if (is_string($token)) {
            return static::fromCharacter($token);
        }

        return static::fromArray($token);
    }

    /**
     * @param array $token
     *
     * @return Token
     */
    static public function fromArray(array $token)
    {
        return new static($token[0], $token[1]);
    }

    /**
     * @param string $token
     *
     * @return Token
     */
    static public function fromCharacter($token)
    {
        return new static($token, $token);
    }

    /**
     * @param integer|string $type
     *
     * @return string|null
     */
    static public function nameByType($type)
    {
        foreach (static::types() as $name => $value) {
            if ($value === $type) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param integer|string $type
     * @param string $content
     */
    public function __construct($type, $content)
    {
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * @return integer|string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @param string $content
     */
    public function append($content)
    {
        $this->content .= $content;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function name()
    {
        return static::nameByType($this->type);
    }

    /**
     * @return boolean
     */
    public function supported()
    {
        return null !== $this->name();
    }

    /**
     * @return string
     */
    public function string()
    {
        return $this->content();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string();
    }

    /**
     * @return array
     */
    static protected function types()
    {
        if (null !== static::$types) {
            return static::$types;
        }

        static::$types = array();
        $reflector = new ReflectionClass(get_called_class());

        foreach ($reflector->getConstants() as $name => $value) {
            if ('TOKEN_' == substr($name, 0, 6)) {
                static::$types[substr($name, 6)] = $value;
            }
        }

        return static::$types;
    }

    const TOKEN_AND = '&';
    const TOKEN_BOOLEAN_FALSE = 'false';
    const TOKEN_BOOLEAN_TRUE = 'true';
    const TOKEN_BRACE_CLOSE = '}';
    const TOKEN_BRACE_OPEN = '{';
    const TOKEN_COLON = ':';
    const TOKEN_COMMA = ',';
    const TOKEN_END = 'end';
    const TOKEN_FLOAT = T_DNUMBER;
    const TOKEN_GREATER_THAN = '>';
    const TOKEN_INTEGER = T_LNUMBER;
    const TOKEN_LESS_THAN = '<';
    const TOKEN_NULL = 'null';
    const TOKEN_PARENTHESIS_CLOSE = ')';
    const TOKEN_PARENTHESIS_OPEN = '(';
    const TOKEN_PIPE = '|';
    const TOKEN_SQUARE_BRACKET_CLOSE = ']';
    const TOKEN_SQUARE_BRACKET_OPEN = '[';
    const TOKEN_STRING = T_STRING;
    const TOKEN_STRING_QUOTED = T_CONSTANT_ENCAPSED_STRING;
    const TOKEN_WHITESPACE = T_WHITESPACE;

    /**
     * @var array
     */
    static protected $types;

    /**
     * @var integer|string
     */
    protected $type;

    /**
     * @var string
     */
    protected $content;
}
