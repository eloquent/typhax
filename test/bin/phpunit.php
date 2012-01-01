<?php

/*
 * This file is part of the Typhax package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// path constants
require dirname(__DIR__).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'paths.php';

if (!isset($configFile))
{
  $configFile = 'config.xml';
}
$configPath = TYPHAX_TEST_CONFIG_DIR.DIRECTORY_SEPARATOR.$configFile;

$command = 'phpunit --verbose --configuration '.escapeshellarg($configPath);

$arguments = $_SERVER['argv'];
array_shift($arguments);
if ($arguments) {
  $command .= ' '.implode(' ', array_map('escapeshellarg', $arguments));
}

passthru($command, $code);
exit($code);
