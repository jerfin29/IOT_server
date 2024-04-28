<?php

class WireGuardInfo {
    
    private $device;

    public function __construct($device) {
        $this->device = $device;
    }

    public function getPeers() {
        // Full path to wg command and sudo
        $cmd = "/usr/bin/sudo /usr/bin/wg show $this->device";
        
        // Execute the command
        $output = trim(shell_exec($cmd));
        
        // Process the output
        $result = explode(PHP_EOL, $output);
        $interface_out = array_slice($result, 0, 4);
        $peers_out = array_slice($result, 5);
        $peers = array();
        $interface = array();
        $peer_count = -1;

        // Process interface information
        foreach ($interface_out as $value) {
            $value = trim($value);
            $data = explode(':', $value);
            $interface[trim($data[0])] = trim($data[1]);
        }

        // Add allowed ips to the interface
        $interface['allowed ips'] = $this->getCIDR();

        // Process peers information
        foreach ($peers_out as $value) {
            $value = trim($value);
            if (strlen($value) > 1) {
                if ($this->startsWith($value, 'peer')) {
                    $peer_count++;
                }
                $data = explode(':', $value);
                $peers[$peer_count][trim($data[0])] = trim($data[1]);
            }
        }

        // Return the result
        return [
            'interface' => $interface,
            'peers' => $peers
        ];
    }

    public function getCIDR() {
        // Full path to wg configuration file
        $configFile = "/etc/wireguard/$this->device.conf";

        // Command to read the first 3 lines of the configuration file
        $cmd = "/usr/bin/sudo /bin/cat $configFile | /usr/bin/head -n 3";

        // Execute the command
        $output = trim(shell_exec($cmd));

        // Split the output into lines
        $lines = explode(PHP_EOL, $output);

        // Iterate through the lines to find the "Address" and extract its value
        foreach ($lines as $line) {
            // Split each line into key-value pairs
            $lineParts = explode('=', $line);

            // Check if the key is "Address"
            if (trim($lineParts[0]) == "Address") {
                // Return the CIDR value
                return trim($lineParts[1]);
            }
        }

        // Return null if CIDR is not found
        return null;
    }

    private function startsWith($haystack, $needle) {
        // Implement startsWith function
        return (strncmp($haystack, $needle, strlen($needle)) === 0);
    }
}

// Example usage
$device = 'wg0';
$wgInfo = new WireGuardInfo($device);
$result = $wgInfo->getCIDR();

// Display the result
echo '<pre>';
print_r($result);
echo '</pre>';
?>
