<?php
/**
 * This file is part of the "cosma/phest" project
 *
 * (c) Cosmin Voicu<cosmin.voicu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

date_default_timezone_set('UTC');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$environment = getenv('ENV');
define('ENVIRONMENT', $environment ?: 'test');
