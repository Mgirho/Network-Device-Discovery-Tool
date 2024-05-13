<?php

header('Content-Type: application/json');

// variables according to my MySQL setup
$host = 'localhost';
$username = 'root';
$database = 'networkdevice';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$database", $username);

    // Get the JSON data sent from the client
    $data = file_get_contents("php://input");
    $jsonData = json_decode($data);

    // Extract relevant fields
    $ipAddress = $jsonData->ipAddress;

    // Fetch additional information based on IP (replace this with your logic)
    $additionalInfo = getAdditionalInfo($ipAddress);

    $stmtCreateDevicesTable = $db->query('CREATE TABLE IF NOT EXISTS Devices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hostName VARCHAR(255) NOT NULL,
        ipAddress VARCHAR(255) NOT NULL,
        orgz VARCHAR(255),
        loc VARCHAR(255),
        timeZone VARCHAR(255),
        timeAdded TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )');
    

    // Insert data into the table
    $stmtInsertDevice = $db->prepare('INSERT INTO Devices (hostName, ipAddress, orgz, loc, timeZone) VALUES (?, ?, ?, ?, ?)');
    $stmtInsertDevice->execute([
    $additionalInfo['hostName'],
    $ipAddress,
    $additionalInfo['orgz'],
    $additionalInfo['loc'],
    $additionalInfo['timeZone']
    ]);

    // Send a response back to the client
    $response = array('status' => 'success', 'message' => 'Device data saved successfully');
    echo json_encode($response);

    // Close the database connection
    $db = null;

} catch (PDOException $e) {
    // Handle database errors
    $response = array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
    echo json_encode($response);
}

function getAdditionalInfo($ipAddress)
{
    // For example, you might use a service like ipinfo.io
    $url = "https://ipinfo.io/$ipAddress/json";
    $ipInfo = json_decode(file_get_contents($url), true);

    // Example - extract data from ipinfo.io
    return [
        'hostName' => $ipInfo['hostname'] ?? '',
        'orgz' => $ipInfo['org'] ?? '',
        'loc' => $ipInfo['loc'] ?? '',
        'timeZone' => $ipInfo['timezone'] ?? ''
    ];
}
?>
