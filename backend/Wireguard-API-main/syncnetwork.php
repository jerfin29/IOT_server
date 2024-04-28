<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/api/lib/Wireguard.class.php';
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/IPNetwork.class.php");
if(!isset($_GET["if"]) and !isset($_GET['token'])){
    die("Dead on syncnetwork");
}

if($_GET['token'] != '09b693f6-e6b4-4cdc-a7c6-14a337fae61d'){
    die("Not authorized");
}
$wg = new Wireguard($_GET["if"]);
print($wg->getCIDR());
$ip = new IPNetwork($wg->getCIDR(), $wg->device);
//print($ip->getNextInsertID());
print_r($ip->getNetwork());
$ip->constructNetworkFile($wg->device);
try {
    print_r($ip->syncNetworkFile($wg->device));
} catch (Exception $e) {
    print("Network already synced $e");
}