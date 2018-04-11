<?php
/* mls to be run - name is pulled from argv,
*which is used to open a folder with config vars 
*/

$mls = $argv[1];
require("./mlsconfig/$mls/config.php");

date_default_timezone_set('America/Chicago');

//PHRETS
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

$results = $rets->Search($resource, $class, $query);

// or with the additional options (with defaults shown)

$results = $rets->Search(
    $resource,
    $class,
    $query,
    [
        'QueryType' => 'DMQL2',
        'Count' => 1, // count and records
        'Format' => 'COMPACT-DECODED',
        'Limit' => 5,
        'StandardNames' => 0, // give system names
    ]
);

foreach ($results as $record) {
    echo $record['LIST_105'].' '.$record['LIST_0'].' '.$record['LIST_46'].' '.$record['LIST_47'].'\n';
}
?>
