<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Eloquent', __DIR__.'/src');

Phake::setClient(Phake::CLIENT_PHPUNIT);
