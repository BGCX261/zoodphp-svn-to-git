<?php
$argv = $_SERVER['argv'];

if (count($argv) != 2) {
    exit("usage: php -f ".__FILE__." -- /MODULE/CONTROLLER.ACTION/K1/V1/K2/V2/../Kn/Vn\r\n");
}

$_SERVER['REQUEST_URI'] = $argv[1];

define('ZOOD_IN_CONSOLE', true);
define('ZOOD_BIN_PATH', dirname(__FILE__));

require_once dirname(__FILE__) . '/../../public/index.php';