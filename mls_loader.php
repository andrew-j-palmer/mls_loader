<?php
//mls to be run is pulled from argv
$mls = $argv[1];

require("./mlsconfig/$mls/config.php");

date_default_timezone_set('America/New_York');

require_once("vendor/autoload.php");

$config = new \PHRETS\Configuration;
$config->setLoginUrl("$url")
        ->setUsername("$user")
        ->setPassword("$pass")
        ->setRetsVersion("$version");

$rets = new \PHRETS\Session($config);

if ($connect = $rets->Login()) {
    echo "connected";
} else {
    echo "connection failed";
}

?>
