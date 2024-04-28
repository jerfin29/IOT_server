<?php
require 'vendor/autoload.php';
// use MongoDB\Operation\BulkWrite;

require_once $_SERVER['DOCUMENT_ROOT'].'/api/lib/Wireguard.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/api/lib/IPNetwork.class.php';


$ip = new IPNetwork('172.20.0.0/16','wg0');


$wg = new Wireguard("wg0");

// print_r($wg->getCIDR());
 
// print_r($ip->getNetwork());


print_r($ip->syncNetworkFile());

// print_r($ip->getNextIP());