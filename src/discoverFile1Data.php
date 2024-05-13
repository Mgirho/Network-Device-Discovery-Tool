<?php

header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$database = 'networkdevice';

$ipinfoApiKey = 'f52b4062e6292c';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$database", $username);

    // Get the JSON data sent from the client
    $data = file_get_contents("php://input");
    $jsonData = json_decode($data);

    // Extract data from the JSON object
    $networkName = $jsonData->networkName;
    $startipAddress = $jsonData->startipAddress;
    $endipAddress = $jsonData->endipAddress;

    // Create a table for network devices if it doesn't exist
    $tableName = 'NetworkDevices_' . md5($networkName . $startipAddress . $endipAddress);
    $stmtCreateNetworkDeviceTable = $db->query("CREATE TABLE IF NOT EXISTS $tableName (
        id INT AUTO_INCREMENT PRIMARY KEY,
        deviceName VARCHAR(255) NOT NULL,
        ipAddress VARCHAR(255) NOT NULL,
        hostname VARCHAR(255) NOT NULL,
        city VARCHAR(255) NOT NULL,
        region VARCHAR(255) NOT NULL,
        country VARCHAR(255) NOT NULL,
        loc VARCHAR(255) NOT NULL,
        org VARCHAR(255) NOT NULL,
        postal VARCHAR(255) NOT NULL,
        timezone VARCHAR(255) NOT NULL,
        timeAdded TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Perform network discovery logic based on the provided IP range
    $discoveredDevices = discoverDevicesInRange($startipAddress, $endipAddress);

    foreach ($discoveredDevices as $device) {
        // Insert each discovered device into the table
        $stmtInsertNetworkDevice = $db->prepare("INSERT INTO $tableName (
            deviceName, ipAddress, 
            hostname, city, region, country, 
            loc, org, postal, timezone
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmtInsertNetworkDevice->execute([
            $device['deviceName'],
            $device['ipAddress'],
            $device['hostname'],
            $device['city'],
            $device['region'],
            $device['country'],
            $device['loc'],
            $device['org'],
            $device['postal'],
            $device['timezone']
        ]);
    }

    // Respond to the client
    $response = "Network Name: $networkName, Start IP: $startipAddress, End IP: $endipAddress\n";
    $response .= "Discovered Devices:\n" . print_r($discoveredDevices, true);
    echo json_encode(['status' => 'success', 'message' => $response]);

    // Close the database connection
    $db = null;

} catch (PDOException $e) {
    // Handle database errors
    $response = array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
    echo json_encode($response);
}

// Function to perform network discovery based on the provided IP range
function discoverDevicesInRange($startIP, $endIP) {

    $discoveredDevices = [];
    $currentIP = ip2long($startIP);

    while ($currentIP <= ip2long($endIP)) {
        // Use the IPinfo API to get detailed information based on the IP address
        $ipInfo = getIpInfo(long2ip($currentIP));

        // Add the discovered device information to the array
        $discoveredDevices[] = [
            'deviceName' => $ipInfo['hostname'],
            'ipAddress' => long2ip($currentIP),
            'hostname' => $ipInfo['hostname'],
            'city' => $ipInfo['city'],
            'region' => $ipInfo['region'],
            'country' => $ipInfo['country'],
            'loc' => $ipInfo['loc'],
            'org' => $ipInfo['org'],
            'postal' => $ipInfo['postal'],
            'timezone' => $ipInfo['timezone']
        ];

        $currentIP++;
    }

    return $discoveredDevices;
}

// Function to get IP information using the IPinfo API
function getIpInfo($ipAddress) {
    global $ipinfoApiKey;
    $url = "http://ipinfo.io/{$ipAddress}?token={$ipinfoApiKey}";
    $ipInfoJson = file_get_contents($url);
    return json_decode($ipInfoJson, true);
}
?>
