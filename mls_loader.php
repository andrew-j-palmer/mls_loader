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
    echo "connected to $mls \n";
} else {
    echo "connection failed\n";
}

$system = $rets->GetSystemMetadata();

$resources = $system->getResources();
$classes = $resources->first()->getClasses();
var_dump($classes);
?>
