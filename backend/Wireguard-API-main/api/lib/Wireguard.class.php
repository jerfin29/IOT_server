<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/IPNetwork.class.php");
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Carbon\Carbon;

class Wireguard{
    public $db;

    public $device;
    public function __construct($device){
        $this->device = $device;
        $this->db = Database::getConnection();
    }

    //TODO: Need to check if the code presensts some data leak threats.
    public function getCIDR() {
        $cmd = "sudo cat /etc/wireguard/$this->device.conf | head -n 3";
        $line = trim(shell_exec($cmd));
        $lines = explode(PHP_EOL, $line);
        foreach ($lines as $line) {
            $line = explode('=', $line);
            if(trim($line[0]) == "Address"){
                return trim($line[1]);
            }
        }
        
    }    

    public function getPeers() {
        $cmd = "sudo wg show $this->device";
        $output = trim(shell_exec($cmd));
        $result = explode(PHP_EOL, $output);
        $interface_out = array_slice($result, 0, 4);
        $peers_out = array_slice($result, 5);
        $peers = array();
        $interface = array();
        $peer_count = -1;
        foreach($interface_out as $value) {
            $value = trim($value);
            $data = explode(':', $value);
            $interface[trim($data[0])] = trim($data[1]);
        }
        $interface['allowed ips'] = $this->getCIDR();
        
        foreach($peers_out as $value) {
            $value = trim($value);
            if(strlen($value) > 1){
                if(startsWith($value, 'peer')){
                    $peer_count++;
                }
                $data = explode(':', $value);
                $peers[$peer_count][trim($data[0])] = trim($data[1]);
            }
        }
        
        return [
            'interface' => $interface,
            'peers' => $peers
        ];
    }

    public function removePeer($public, $reserved){
        $ipnet = new IPNetwork($this->getCIDR(), $this->device);
        $cmd = "sudo wg set $this->device peer \"$public\" remove";
        $result = 0;
        system($cmd, $result);
        system("sudo wg-quick save $this->device");
        if($result == 0){
            return $ipnet->unallocateIP($public, $reserved);
        }
    }

    public function addPeer($public, $email, $reserved, $ip=null){
        if(!$this->hasPeer($public)){
            $ipnet = new IPNetwork($this->getCIDR(), $this->device);
            $next_ip = $ipnet->getNextIP($email, $ip);
            $cmd = "sudo wg set $this->device peer \"$public\" allowed-ips \"$next_ip/32\"";
            system($cmd, $result);
            system("sudo wg-quick save $this->device", $result1);
            if($result == 0 and $result1 == 0){
                return $ipnet->allocateIP($next_ip, $email, $public, boolval($reserved));
            } else {
                return false;
            }
        } else {
            throw new Exception("Peer already exists");
        }
    }

    public function hasPeer($public){
        return count($this->getPeer($public)) >= 1;
    }

    public function getPeer($public){
        $cmd = "sudo wg show $this->device | grep -A4 '$public'";
        $output = trim(shell_exec($cmd));
        // echo shell_exec('whoami');
        $result = explode(PHP_EOL, $output);
        $peer = array();
        $peer_count = 0;
        foreach($result as $value){
            if(!empty($value)){
                $value = trim($value);
                if(startsWith($value, 'peer:')){
                    $peer_count++;
                    if($peer_count >= 2){
                        break;
                    }
                }
                $data = explode(': ', $value);
                $peer[$data[0]] = $data[1];
            }
        }
        return $peer;
    }

    public function reserve($ip, $email){
        $ipnet = new IPNetwork($this->getCIDR(), $this->device);
        return $ipnet->reserveIP($email, $ip, true);
    }

    public function unreserve($ip, $email){
        $ipnet = new IPNetwork($this->getCIDR(), $this->device);
        return $ipnet->reserveIP($email, $ip, false);
    }
    
}