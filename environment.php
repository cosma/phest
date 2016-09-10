<?php

date_default_timezone_set('UTC');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$environment = getenv('ENV');
define('ENVIRONMENT', $environment ?: 'test');
