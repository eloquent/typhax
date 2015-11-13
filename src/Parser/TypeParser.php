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

use Eloquent\Typhax\Parser\Exception\ParseException;
use Eloquent\Typhax\Parser\Exception\UnexpectedTokenException;
use Eloquent\Typhax\Parser\Exception\UnsupportedAttributeException;
use Eloquent\Typhax\Type\ArrayType;
use Eloquent\Typhax\Type\BooleanType;
use Eloquent\Typhax\Type\CallableType;
use Eloquent\Typhax\Type\ExtensionType;
use Eloquent\Typhax\Type\FloatType;
use Eloquent\Typhax\Type\IntegerType;
use Eloquent\Typhax\Type\MixedType;
use Eloquent\Typhax\Type\NullType;
use Eloquent\Typhax\Type\NumericType;
use Eloquent\Typhax\Type\ObjectType;
use Eloquent\Typhax\Type\OrType;
use Eloquent\Typhax\Type\ResourceType;
use Eloquent\Typhax\Type\StreamType;
use Eloquent\Typhax\Type\StringableType;
use Eloquent\Typhax\Type\StringType;
use Eloquent\Typhax\Type\TraversablePrimaryType;
use Eloquent\Typhax\Type\TraversableType;
use Eloquent\Typhax\Type\TupleType;
use Eloquent\Typhax\Type\Type;

/**
 * Parses Typhax type expressions.
 *
 * @api
 */
class TypeParser
{
    /**
     * Create a new type parser.
     *
     * @api
     *
     * @return self The parser.
     */
    public static function create()
    {
        return new self(new TypeLexer());
    }

    /**
     * Construct a new type parser.
     *
     * @param TypeLexer $lexer The lexer to use.
     */
    public function __construct(TypeLexer $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Parse the supplied source into a type.
     *
     * The `$offset` argument will be set to the final parsing offset upon
     * completion.
     *
     * @api
     *
     * @param string  $source  The source.
     * @param integer &$offset The offset to start parsing at.
     *
     * @return Type           The parsed type.
     * @throws ParseException If the source cannot be parsed.
     */
    public function parse($source, &$offset = 0)
    {
        $tokens = $this->lexer->tokens($source, $offset);
        $type = $this->parseTokens($tokens, $index);
        $offset += self::offset($tokens, $index);

        return $type;
    }

    /**
     * Calculate the offset from a list of tokens.
     *
     * @param array<TypeToken> $tokens The tokens.
     * @param integer|null     $index  The index at which parsing stopped.
     *
     * @return integer The offset.
     */
    public static function offset(array $tokens, $index)
    {
        if (count($tokens) < 1) {
            return 0;
        }

        if (null === $index) {
            $index = count($tokens) - 1;
        }

        $source = '';

        for ($i = 0; $i < $index; ++$i) {
            $source .= $tokens[$i]->content();
        }

        return strlen($source);
    }

    private function parseTokens(&$tokens, &$index)
    {
        $type = $this->parseType($tokens, $index);
        $this->consumeWhitespace($tokens);

        if (!$this->endReached($tokens)) {
            $type = $this->parseComposite($tokens, $index, $type, 0);
        }

        return $type;
    }

    private function parseType(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);

        $token = $this->assert(
            $tokens,
            array(
                TypeToken::TOKEN_STRING,
                TypeToken::TOKEN_TYPE_NAME,
                TypeToken::TOKEN_NULL,
                TypeToken::TOKEN_COLON,
            )
        );

        if (TypeToken::TOKEN_STRING === $token->type()) {
            $type = new ObjectType($token->content());
            next($tokens);
            $index = key($tokens);
        } elseif (TypeToken::TOKEN_COLON === $token->type()) {
            next($tokens);
            $index = key($tokens);
            $type = $this->parseExtensionType($tokens, $index);
        } else {
            $type = $this->parseTypeName($tokens, $index);
        }

        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_LESS_THAN)) {
            if (!$type instanceof TraversablePrimaryType) {
                throw new UnexpectedTokenException(
                    current($tokens)->name(),
                    self::offset($tokens, key($tokens)),
                    TypeToken::namesByType(
                        array(
                            TypeToken::TOKEN_BRACE_OPEN,
                            TypeToken::TOKEN_PLUS,
                            TypeToken::TOKEN_PIPE,
                        )
                    )
                );
            }

            $count = 0;
            $types = $this->parseTypeList(
                $tokens,
                $index,
                function () use (&$tokens, &$count) {
                    ++$count;
                    if ($count > 1) {
                        throw new UnexpectedTokenException(
                            TypeToken::nameByType(TypeToken::TOKEN_COMMA),
                            TypeParser::offset($tokens, key($tokens)),
                            TypeToken::namesByType(
                                array(TypeToken::TOKEN_GREATER_THAN)
                            )
                        );
                    }
                }
            );

            if (1 === count($types)) {
                $keyType = null;
                list($valueType) = $types;
            } else {
                list($keyType, $valueType) = $types;
            }

            $type = new TraversableType($type, $keyType, $valueType);
        }

        if ($type instanceof ArrayType) {
            $type = new TraversableType(
                $type,
                new MixedType(),
                new MixedType()
            );
        }

        return $type;
    }

    private function parseTypeName(&$tokens, &$index)
    {
        $token = $this->assert(
            $tokens,
            array(TypeToken::TOKEN_TYPE_NAME, TypeToken::TOKEN_NULL)
        );

        if ('resource' === $token->content()) {
            return $this->parseResourceType($tokens, $index);
        }

        if ('stream' === $token->content()) {
            return $this->parseStreamType($tokens, $index);
        }

        if ('tuple' === $token->content()) {
            return $this->parseTupleType($tokens, $index);
        }

        next($tokens);
        $index = key($tokens);

        switch ($token->content()) {
            case 'array':
                return new ArrayType();
            case 'boolean':
            case 'bool':
                return new BooleanType();
            case 'callable':
            case 'callback':
                return new CallableType();
            case 'float':
            case 'double':
            case 'real':
                return new FloatType();
            case 'integer':
            case 'int':
            case 'long':
                return new IntegerType();
            case 'null':
                return new NullType();
            case 'number':
                return new OrType(array(
                    new IntegerType(),
                    new FloatType(),
                ));
            case 'numeric':
                return new NumericType();
            case 'object':
                return new ObjectType();
            case 'scalar':
                return new OrType(array(
                    new IntegerType(),
                    new FloatType(),
                    new StringType(),
                    new BooleanType(),
                ));
            case 'string':
                return new StringType();
            case 'stringable':
                return new StringableType();
        }

        return new MixedType();
    }

    private function parseExtensionType(array &$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);

        $token = $this->assert($tokens, TypeToken::TOKEN_STRING);

        next($tokens);
        $index = key($tokens);

        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_LESS_THAN)) {
            $subTypes = $this->parseTypeList($tokens, $index, null);
        } else {
            $subTypes = array();
        }

        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_BRACE_OPEN)) {
            $attributes = $this->parseAttributes(
                $tokens,
                $index,
                ':' . $token->content(),
                null
            );
        } else {
            $attributes = array();
        }

        return new ExtensionType($token->content(), $subTypes, $attributes);
    }

    private function parseResourceType(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_TYPE_NAME);
        next($tokens);
        $index = key($tokens);

        $this->consumeWhitespace($tokens);
        $ofType = null;

        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_BRACE_OPEN)) {
            $attributes = $this
                ->parseAttributes($tokens, $index, 'resource', array('ofType'));

            if (array_key_exists('ofType', $attributes)) {
                $ofType = $attributes['ofType'];
            }
        }

        return new ResourceType($ofType);
    }

    private function parseStreamType(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_TYPE_NAME);
        next($tokens);
        $index = key($tokens);

        $this->consumeWhitespace($tokens);
        $readable = null;
        $writable = null;

        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_BRACE_OPEN)) {
            $attributes = $this->parseAttributes(
                $tokens,
                $index,
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

    private function parseTupleType(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_TYPE_NAME);
        next($tokens);
        $index = key($tokens);

        $this->consumeWhitespace($tokens);
        $types = $this->parseTypeList($tokens, $index, null);

        return new TupleType($types);
    }

    private function parseTypeList(&$tokens, &$index, $commaCallback)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_LESS_THAN);
        next($tokens);
        $index = key($tokens);

        $types = array($this->parseTokens($tokens, $index));

        $this->consumeWhitespace($tokens);

        while ($this->currentTokenIsType($tokens, TypeToken::TOKEN_COMMA)) {
            if ($commaCallback) {
                $commaCallback();
            }

            next($tokens);
            $index = key($tokens);
            $types[] = $this->parseTokens($tokens, $index);
            $this->consumeWhitespace($tokens);
        }

        $this->assert($tokens, TypeToken::TOKEN_GREATER_THAN);
        next($tokens);
        $index = key($tokens);

        return $types;
    }

    private function parseAttributes(
        &$tokens,
        &$index,
        $typeName,
        $supportedAttributes
    ) {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_BRACE_OPEN);
        next($tokens);
        $index = key($tokens);
        $this->consumeWhitespace($tokens);

        $attributes = $this->parseHashContents(
            $tokens,
            $index,
            function ($attribute) use (
                &$tokens, // @codeCoverageIgnore
                $typeName,
                $supportedAttributes
            ) {
                if (
                    $supportedAttributes !== null &&
                    !in_array($attribute, $supportedAttributes)
                ) {
                    throw new UnsupportedAttributeException(
                        $typeName,
                        $attribute,
                        TypeParser::offset($tokens, key($tokens)) -
                            strlen($attribute)
                    );
                }
            }
        );

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_BRACE_CLOSE);
        next($tokens);
        $index = key($tokens);

        return $attributes;
    }

    private function parseValue(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);

        $token = $this->assert(
            $tokens,
            array(
                TypeToken::TOKEN_STRING,
                TypeToken::TOKEN_STRING_QUOTED,
                TypeToken::TOKEN_TYPE_NAME,
                TypeToken::TOKEN_INTEGER,
                TypeToken::TOKEN_FLOAT,
                TypeToken::TOKEN_NULL,
                TypeToken::TOKEN_BOOLEAN_TRUE,
                TypeToken::TOKEN_BOOLEAN_FALSE,
                TypeToken::TOKEN_BRACE_OPEN,
                TypeToken::TOKEN_SQUARE_BRACKET_OPEN,
            )
        );

        if (TypeToken::TOKEN_BRACE_OPEN === $token->type()) {
            return $this->parseHash($tokens, $index);
        }

        if (TypeToken::TOKEN_SQUARE_BRACKET_OPEN === $token->type()) {
            return $this->parseArray($tokens, $index);
        }

        next($tokens);
        $index = key($tokens);

        switch ($token->type()) {
            case TypeToken::TOKEN_STRING_QUOTED:
                return substr($token->content(), 1, -1);
            case TypeToken::TOKEN_INTEGER:
                return intval($token->content());
            case TypeToken::TOKEN_FLOAT:
                return floatval($token->content());
            case TypeToken::TOKEN_NULL:
                return null;
            case TypeToken::TOKEN_BOOLEAN_TRUE:
                return true;
            case TypeToken::TOKEN_BOOLEAN_FALSE:
                return false;
        }

        return $token->content();
    }

    private function parseHash(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_BRACE_OPEN);
        next($tokens);
        $index = key($tokens);
        $this->consumeWhitespace($tokens);

        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_BRACE_CLOSE)) {
            next($tokens);
            $index = key($tokens);

            return array();
        }

        $hash = $this->parseHashContents($tokens, $index, null);

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_BRACE_CLOSE);
        next($tokens);
        $index = key($tokens);

        return $hash;
    }

    private function parseHashContents(&$tokens, &$index, $keyCallback)
    {
        $hash = array();

        while (true) {
            $key = $this->parseValue($tokens, $index);

            if ($keyCallback) {
                $keyCallback($key);
            }

            $this->consumeWhitespace($tokens);

            $this->assert($tokens, TypeToken::TOKEN_COLON);
            next($tokens);
            $index = key($tokens);

            $hash[$key] = $this->parseValue($tokens, $index);

            $this->consumeWhitespace($tokens);

            if (!$this->currentTokenIsType($tokens, TypeToken::TOKEN_COMMA)) {
                break;
            }

            next($tokens);
            $index = key($tokens);
        }

        return $hash;
    }

    private function parseArray(&$tokens, &$index)
    {
        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_SQUARE_BRACKET_OPEN);
        next($tokens);
        $index = key($tokens);
        $this->consumeWhitespace($tokens);

        if (
            $this->currentTokenIsType(
                $tokens,
                TypeToken::TOKEN_SQUARE_BRACKET_CLOSE
            )
        ) {
            next($tokens);
            $index = key($tokens);

            return array();
        }

        $array = array();

        while (true) {
            $array[] = $this->parseValue($tokens, $index);
            $this->consumeWhitespace($tokens);

            if (!$this->currentTokenIsType($tokens, TypeToken::TOKEN_COMMA)) {
                break;
            }

            next($tokens);
            $index = key($tokens);
        }

        $this->consumeWhitespace($tokens);
        $this->assert($tokens, TypeToken::TOKEN_SQUARE_BRACKET_CLOSE);
        next($tokens);
        $index = key($tokens);

        return $array;
    }

    private function parseComposite(
        &$tokens,
        &$index,
        $left,
        $minimumPrecedence
    ) {
        while (
            $minimumPrecedence <=
                ($precedence = $this->getCompositePrecedence($tokens))
        ) {
            $operator = current($tokens)->content();
            next($tokens);
            $index = key($tokens);
            $right = $this->parseType($tokens, $index);

            if ($precedence < $this->getCompositePrecedence($tokens)) {
                $right = $this->parseComposite(
                    $tokens,
                    $index,
                    $right,
                    $precedence + 1
                );
            }

            $left = $this->makeComposite($operator, $left, $right);
        }

        return $left;
    }

    private function makeComposite($operator, $left, $right)
    {
        if (TypeToken::TOKEN_PLUS === $operator) {
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

    private function getCompositePrecedence(&$tokens)
    {
        if (!$token = current($tokens)) {
            return -1;
        }

        $precedence =
            array_search($token->type(), $this->compositePrecedence, true);

        if (false === $precedence) {
            $precedence = -1;
        }

        return $precedence;
    }

    private function assert(&$tokens, $types)
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

        throw new UnexpectedTokenException(
            $tokenName,
            self::offset($tokens, key($tokens)),
            TypeToken::namesByType($types)
        );
    }

    private function consumeWhitespace(&$tokens)
    {
        if ($this->currentTokenIsType($tokens, TypeToken::TOKEN_WHITESPACE)) {
            next($tokens);
        }
    }

    private function endReached(&$tokens)
    {
        return null === key($tokens);
    }

    private function currentTokenIsType(&$tokens, $type)
    {
        return !$this->endReached($tokens) &&
            current($tokens)->type() === $type;
    }

    private $lexer;
    private $compositePrecedence = array(
        TypeToken::TOKEN_PIPE,
        TypeToken::TOKEN_PLUS,
    );
}
