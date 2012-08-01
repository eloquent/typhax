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
use Eloquent\Typhax\Lexer\Token;
use Eloquent\Typhax\Type\AndType;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallbackType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumberType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StringType;
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
        $index = key($tokens);

        $source = '';
        for ($i = 0; $i <= $index; $i ++) {
            $source .= $tokens[$i]->content();
        }

        return mb_strlen($source, 'UTF-8');
    }

    /**
     * @param array<integer,Token> &$tokens
     *
     * @return Type
     */
    public function parse(array &$tokens)
    {
        $type = $this->parseType($tokens);
        $this->consumeWhitespace($tokens);

        if (Token::TOKEN_END !== current($tokens)->type()) {
            $type = $this->parseComposite($tokens, $type);
        }

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, Token::TOKEN_END);

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

        if (Token::TOKEN_LESS_THAN === current($tokens)->type()) {
            if (!$type instanceof TraversablePrimaryType) {
                throw new Exception\UnexpectedTokenException(
                    current($tokens)->name()
                    , $this->position($tokens)
                    , Token::typesToNames(array(
                        Token::TOKEN_PARENTHESIS_OPEN,
                        Token::TOKEN_AND,
                        Token::TOKEN_PIPE,
                        Token::TOKEN_END,
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
        if ('tuple' === $token->content()) {
            return $this->parseTupleType($tokens);
        }

        next($tokens);

        switch ($token->content()) {
            case 'array':
                return new ArrayType;
            case 'boolean':
                return new BooleanType;
            case 'callback':
                return new CallbackType;
            case 'float':
                return new FloatType;
            case 'integer':
                return new IntegerType;
            case 'null':
                return new NullType;
            case 'number':
                return new NumberType;
            case 'numeric':
                return new NumericType;
            case 'object':
                return new ObjectType;
            case 'string':
                return new StringType;
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
        if (Token::TOKEN_PARENTHESIS_OPEN === current($tokens)->type()) {
            $attributes = $this->parseAttributes(
                $tokens,
                'resource',
                array('ofType')
            );
            $ofType = $attributes['ofType'];
        }

        return new ResourceType($ofType);
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
            $this->parseType($tokens)
        );

        $this->consumeWhitespace($tokens);

        while (Token::TOKEN_COMMA === current($tokens)->type()) {
            if ($commaCallback) {
                $commaCallback();
            }

            next($tokens);
            $types[] = $this->parseType($tokens);
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
        $this->assert($tokens, Token::TOKEN_PARENTHESIS_OPEN);
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
        $this->assert($tokens, Token::TOKEN_PARENTHESIS_CLOSE);
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

        if (Token::TOKEN_BRACE_CLOSE === current($tokens)->type()) {
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
            if (Token::TOKEN_COMMA !== current($tokens)->type()) {
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

        if (Token::TOKEN_SQUARE_BRACKET_CLOSE === current($tokens)->type()) {
            next($tokens);

            return array();
        }

        $array = array();
        while (true) {
            $array[] = $this->parseValue($tokens);
            $this->consumeWhitespace($tokens);

            if (Token::TOKEN_COMMA !== current($tokens)->type()) {
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
        if (Token::TOKEN_AND === $operator) {
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

            if (false !== $precedence) {
                return $precedence;
            }
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

        $token = current($tokens);

        if (!in_array($token->type(), $types, true)) {
            throw new Exception\UnexpectedTokenException(
                $token->name()
                , $this->position($tokens)
                , Token::typesToNames($types)
            );
        }

        return $token;
    }

    protected function consumeWhitespace(array &$tokens)
    {
        if (Token::TOKEN_WHITESPACE === current($tokens)->type()) {
            next($tokens);
        }
    }

    /**
     * @var array<integer|string>
     */
    protected $compositePrecedence = array(
        Token::TOKEN_PIPE,
        Token::TOKEN_AND,
    );
}
