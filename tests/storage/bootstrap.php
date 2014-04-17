<?php

$autoloader = require __DIR__ . '/../../vendor/autoload.php';

define('CONFIG_FILE', __DIR__ . '/_config.php');

// -------
function _dump($value)
{
    error_log(print_r($value, true));
}