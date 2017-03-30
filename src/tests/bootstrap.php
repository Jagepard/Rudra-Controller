<?php

declare(strict_types = 1);

define('DEV', true);
define('BP', dirname(dirname(__DIR__)) . '/');

require_once BP . 'vendor/autoload.php';
require_once dirname(__DIR__) . '/IController.php';
require_once dirname(__DIR__) . '/Controller.php';
