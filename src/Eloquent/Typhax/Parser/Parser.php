<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\Parser;

use Closure;
use Eloquent\Typhax\Lexer\Lexer;
use Eloquent\Typhax\Lexer\Token;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\TraversablePrimaryType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;

class Parser
{
    /**
     * @param array<integer,Token> &$tokens
     *
     * @return integer
     */
    public static function position(array &$tokens)
    {
        if (count($tokens) < 1) {
            return 0;
        }

        $index = key($tokens);
        if (null === $index) {
            $index = count($tokens);
        }

        $source = '';
        for ($i = 0; $i < $index; $i ++) {
            $source .= $tokens[$i]->content();
        }

        return mb_strlen($source, 'UTF-8') + 1;
    }

    /**
     * @param string $source
     * @param integer &$position
     * @param Lexer $lexer
     *
     * @return Type
     */
    public function parse($source, &$position = 0, Lexer $lexer = null)
    {
        if (null === $lexer) {
            $lexer = new Lexer;
        }

        $tokens = $lexer->tokens($source);
        $type = $this->parseTokens($tokens);
        $position = static::position($tokens);

        return $type;
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return Type
     */
    public function parseTokens(array &$tokens)
    {
        $type = $this->parseType($tokens);
        $this->consumeWhitespace($tokens);

        if (!$this->endReached($tokens)) {
            $type = $this->parseComposite($tokens, $type);
        }

        return $type;
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return Type
     */
    protected function parseType(array &$tokens)
    {
        $this->consumeWhitespace($tokens);

        $token = $this->assert(
            $tokens,
            array(
                Token::TOKEN_STRING,
                Token::TOKEN_TYPE_NAME,
                Token::TOKEN_NULL,
            )
        );

        if (Token::TOKEN_STRING === $token->type()) {
            $type = new ObjectType($token->content());
            next($tokens);
        } else {
            $type = $this->parseTypeName($tokens);
        }

        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, Token::TOKEN_LESS_THAN)) {
            if (!$type instanceof TraversablePrimaryType) {
                throw new Exception\UnexpectedTokenException(
                    current($tokens)->name()
                    , $this->position($tokens)
                    , Token::typesToNames(array(
                        Token::TOKEN_BRACE_OPEN,
                        Token::TOKEN_PLUS,
                        Token::TOKEN_PIPE,
                    ))
                );
            }

            $count = 0;
            $types = $this->parseTypeList(
                $tokens,
                function() use(&$tokens, &$count) {
                    $count ++;
                    if ($count > 1) {
                        throw new Exception\UnexpectedTokenException(
                            Token::nameByType(Token::TOKEN_COMMA)
                            , Parser::position($tokens)
                            , Token::typesToNames(array(
                                Token::TOKEN_GREATER_THAN,
                            ))
                        );
                    }
                }
            );

            $typeCount = count($types);
            if (1 === $typeCount) {
                $keyType = new MixedType;
                list($valueType) = $types;
            } else {
                list($keyType, $valueType) = $types;
            }

            $type = new TraversableType($type, $keyType, $valueType);
        }

        if ($type instanceof ArrayType) {
            $type = new TraversableType(
                $type,
                new MixedType,
                new MixedType
            );
        }

        return $type;
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return Type
     */
    protected function parseTypeName(array &$tokens)
    {
        $token = $this->assert($tokens, array(
            Token::TOKEN_TYPE_NAME,
            Token::TOKEN_NULL,
        ));

        if ('resource' === $token->content()) {
            return $this->parseResourceType($tokens);
        }
        if ('stream' === $token->content()) {
            return $this->parseStreamType($tokens);
        }
        if ('tuple' === $token->content()) {
            return $this->parseTupleType($tokens);
        }

        next($tokens);

        switch ($token->content()) {
            case 'array':
                return new ArrayType;
            case 'boolean':
            case 'bool':
                return new BooleanType;
            case 'callable':
            case 'callback':
                return new CallableType;
            case 'float':
            case 'double':
            case 'real':
                return new FloatType;
            case 'integer':
            case 'int':
            case 'long':
                return new IntegerType;
            case 'null':
                return new NullType;
            case 'number':
                return new OrType(array(
                    new IntegerType,
                    new FloatType,
                ));
            case 'numeric':
                return new NumericType;
            case 'object':
                return new ObjectType;
            case 'scalar':
                return new OrType(array(
                    new IntegerType,
                    new FloatType,
                    new StringType,
                    new BooleanType,
                ));
            case 'string':
                return new StringType;
            case 'stringable':
                return new StringableType;
        }

        return new MixedType;
    }

    /**
     * array<integer,Token> &$tokens
     *
     * @return ResourceType
     */
    protected function parseResourceType(array &$tokens)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_TYPE_NAME);
        next($tokens);

        $this->consumeWhitespace($tokens);
        $ofType = null;
        if ($this->currentTokenIsType($tokens, Token::TOKEN_BRACE_OPEN)) {
            $attributes = $this->parseAttributes(
                $tokens,
                'resource',
                array('ofType')
            );
            if (array_key_exists('ofType', $attributes)) {
                $ofType = $attributes['ofType'];
            }
        }

        return new ResourceType($ofType);
    }

    /**
     * array<integer,Token> &$tokens
     *
     * @return StreamType
     */
    protected function parseStreamType(array &$tokens)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_TYPE_NAME);
        next($tokens);

        $this->consumeWhitespace($tokens);
        $readable = null;
        $writable = null;
        if ($this->currentTokenIsType($tokens, Token::TOKEN_BRACE_OPEN)) {
            $attributes = $this->parseAttributes(
                $tokens,
                'stream',
                array('readable', 'writable')
            );
            if (array_key_exists('readable', $attributes)) {
                $readable = $attributes['readable'];
            }
            if (array_key_exists('writable', $attributes)) {
                $writable = $attributes['writable'];
            }
        }

        return new StreamType($readable, $writable);
    }

    /**
     * array<integer,Token> &$tokens
     *
     * @return TupleType
     */
    protected function parseTupleType(array &$tokens)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_TYPE_NAME);
        next($tokens);

        $this->consumeWhitespace($tokens);
        $types = $this->parseTypeList($tokens);

        return new TupleType($types);
    }

    /**
     * @param array<integer,Token> &$tokens
     * @param Closure|null $commaCallback
     *
     * @return Type
     */
    protected function parseTypeList(array &$tokens, Closure $commaCallback = null)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_LESS_THAN);
        next($tokens);

        $types = array(
            $this->parseTokens($tokens)
        );

        $this->consumeWhitespace($tokens);

        while ($this->currentTokenIsType($tokens, Token::TOKEN_COMMA)) {
            if ($commaCallback) {
                $commaCallback();
            }

            next($tokens);
            $types[] = $this->parseTokens($tokens);
            $this->consumeWhitespace($tokens);
        }

        $this->assert($tokens, Token::TOKEN_GREATER_THAN);
        next($tokens);

        return $types;
    }

    /**
     * @param array<integer,Token> &$tokens
     * @param string $typeName
     * @param array<string> $supportedAttributes
     *
     * @return array
     */
    protected function parseAttributes(array &$tokens, $typeName, array $supportedAttributes)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_BRACE_OPEN);
        next($tokens);
        $this->consumeWhitespace($tokens);

        $attributes = $this->parseHashContents(
            $tokens,
            function($attribute) use(&$tokens, $typeName, $supportedAttributes) {
                if (!in_array($attribute, $supportedAttributes)) {
                    throw new Exception\UnsupportedAttributeException(
                        $typeName,
                        $attribute,
                        Parser::position($tokens) - mb_strlen($attribute, 'UTF-8')
                    );
                }
            }
        );

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_BRACE_CLOSE);
        next($tokens);

        return $attributes;
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return mixed
     */
    protected function parseValue(array &$tokens)
    {
        $this->consumeWhitespace($tokens);

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

        if (Token::TOKEN_BRACE_OPEN === $token->type()) {
            return $this->parseHash($tokens);
        }

        if (Token::TOKEN_SQUARE_BRACKET_OPEN === $token->type()) {
            return $this->parseArray($tokens);
        }

        next($tokens);

        switch ($token->type()) {
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
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_BRACE_OPEN);
        next($tokens);
        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, Token::TOKEN_BRACE_CLOSE)) {
            next($tokens);

            return array();
        }

        $hash = $this->parseHashContents($tokens);

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_BRACE_CLOSE);
        next($tokens);

        return $hash;
    }

    /**
     * @param array<integer,Token> &$tokens
     * @param Closure|null $keyCallback
     *
     * @return array
     */
    protected function parseHashContents(array &$tokens, Closure $keyCallback = null)
    {
        $hash = array();
        while (true) {
            $key = $this->parseValue($tokens);
            if ($keyCallback) {
                $keyCallback($key);
            }
            $this->consumeWhitespace($tokens);

            $this->assert($tokens, Token::TOKEN_COLON);
            next($tokens);

            $hash[$key] = $this->parseValue($tokens);

            $this->consumeWhitespace($tokens);
            if (!$this->currentTokenIsType($tokens, Token::TOKEN_COMMA)) {
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
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_SQUARE_BRACKET_OPEN);
        next($tokens);
        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, Token::TOKEN_SQUARE_BRACKET_CLOSE)) {
            next($tokens);

            return array();
        }

        $array = array();
        while (true) {
            $array[] = $this->parseValue($tokens);
            $this->consumeWhitespace($tokens);

            if (!$this->currentTokenIsType($tokens, Token::TOKEN_COMMA)) {
                break;
            }
            next($tokens);
        }

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_SQUARE_BRACKET_CLOSE);
        next($tokens);

        return $array;
    }

    /**
     * @param array<integer,Token> &$tokens
     * @param Type $types
     * @param integer $minimum_precedence
     *
     * @return Type
     */
    protected function parseComposite(array &$tokens, Type $left, $minimum_precedence = 0)
    {
        while ($minimum_precedence <= ($precedence = $this->getCompositePrecedence($tokens))) {
            $operator = current($tokens)->content();

            next($tokens);

            $right = $this->parseType($tokens);

            if ($precedence < $this->getCompositePrecedence($tokens)) {
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
     * @param Type $left
     * @param Type $right
     *
     * @return CompositeType
     */
    protected function makeComposite($operator, Type $left, Type $right)
    {
        if (Token::TOKEN_PLUS === $operator) {
            $compositeType = 'Eloquent\Typhax\Type\AndType';
        } else {
            $compositeType = 'Eloquent\Typhax\Type\OrType';
        }

        if ($left instanceof $compositeType) {
            $types = $left->types();
            $types[] = $right;
        } else {
            $types = array($left, $right);
        }

        return new $compositeType($types);
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return integer
     */
    protected function getCompositePrecedence(array &$tokens) {
        $token = current($tokens);
        if ($token) {
            $precedence = array_search(
                $token->type()
                , $this->compositePrecedence
                , true
            );
            if (false === $precedence) {
                $precedence = -1;
            }

            return $precedence;
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
        if (!is_array($types)) {
            $types = array($types);
        }

        if ($this->endReached($tokens)) {
            $valid = false;
            $tokenName = 'END';
        } else {
            $token = current($tokens);
            $valid = in_array($token->type(), $types, true);
            $tokenName = $token->name();
        }

        if ($valid) {
            return $token;
        }

        throw new Exception\UnexpectedTokenException(
            $tokenName
            , $this->position($tokens)
            , Token::typesToNames($types)
        );
    }

    /**
     * @param array<integer,Token> &$tokens
     */
    protected function consumeWhitespace(array &$tokens)
    {
        if ($this->currentTokenIsType($tokens, Token::TOKEN_WHITESPACE)) {
            next($tokens);
        }
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return boolean
     */
    protected function endReached(&$tokens)
    {
        return null === key($tokens);
    }

    /**
     * @param array<integer,Token> &$tokens
     * @param string $type
     *
     * @return boolean
     */
    protected function currentTokenIsType(&$tokens, $type)
    {
        return
            !$this->endReached($tokens)
            && current($tokens)->type() === $type
        ;
    }

    /**
     * @var array<integer|string>
     */
    protected $compositePrecedence = array(
        Token::TOKEN_PIPE,
        Token::TOKEN_PLUS,
    );
}
