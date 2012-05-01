<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhax\IntrinsicType;

use Eloquent\Enumeration\Multiton;

final class IntrinsicTypeAlias extends Multiton
{
  /**
   * @return string
   */
  public function _value()
  {
    return $this->_value;
  }
  /**
   * @return string
   */
  public function _typeName()
  {
    return $this->_typeName;
  }

  protected static function _initialize()
  {
    parent::_initialize();

    new static('ALIAS_BOOL', 'bool', 'boolean');
    new static('ALIAS_CALLABLE', 'callable', 'callback');
    new static('ALIAS_DOUBLE', 'double', 'float');
    new static('ALIAS_FLOATABLE', 'floatable', 'numeric');
    new static('ALIAS_INT', 'int', 'integer');
    new static('ALIAS_KEYABLE', 'keyable', 'scalar');
    new static('ALIAS_LONG', 'long', 'integer');
    new static('ALIAS_REAL', 'real', 'float');
  }

  /**
   * @param string $key
   * @param string $value
   * @param string $typeName
   */
  protected function __construct($key, $value, $typeName)
  {
    parent::__construct($key);

    $this->_value = $value;
    $this->_typeName = $typeName;
  }

  /**
   * @var string
   */
  protected $_value;

  /**
   * @var string
   */
  protected $_typeName;
}
