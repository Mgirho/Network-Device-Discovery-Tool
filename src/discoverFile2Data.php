<?php

header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$database = 'networkdevice';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$database", $username);

    // Get the JSON data sent from the client
    $data = file_get_contents("php://input");
    $jsonData = json_decode($data);

    // CSV data processing
    $csvFileName = $jsonData->csvFileName;
    $csvData = array_map('str_getcsv', file($csvFileName));

    // Create tables if they don't exist
    createTables($db);

    // Skip the first row (header) in the CSV data
    array_shift($csvData);

    // Loop through each row of CSV data
    foreach ($csvData as $row) {
        $ipAddress = $row[0];
        $deviceName = $row[1];
        $deviceType = $row[2];

        // Insert data into the ApprovedDevice table if conditions are met
        if (shouldInsertApproved($deviceType, $deviceName, $ipAddress)) {
            $stmtInsertApprovedDevice = $db->prepare('INSERT INTO ApprovedDevice (deviceName, ipAddress, deviceType) VALUES (?, ?, ?)');
            $stmtInsertApprovedDevice->execute([$deviceName, $ipAddress, $deviceType]);
        } elseif (shouldInsertUnapproved($deviceType, $deviceName)) {
            // Insert data into the UnapprovedDevice table if conditions are met
            $stmtInsertUnapprovedDevice = $db->prepare('INSERT INTO UnapprovedDevice (deviceName, ipAddress, deviceType) VALUES (?, ?, ?)');
            $stmtInsertUnapprovedDevice->execute([$deviceName, $ipAddress, $deviceType]);
        } elseif (shouldInsertIgnored($deviceType, $deviceName, $ipAddress)) {
            // Insert data into the IgnoredDevice table if conditions are met
            $stmtInsertIgnoredDevice = $db->prepare('INSERT INTO IgnoredDevice (deviceName, ipAddress, deviceType) VALUES (?, ?, ?)');
            $stmtInsertIgnoredDevice->execute([$deviceName, $ipAddress, $deviceType]);
        }
    }

    // Send a response back to the client
    $response = array('status' => 'success', 'message' => 'CSV data processed successfully');
    echo json_encode($response);

    // Close the database connection
    $db = null;

} catch (PDOException $e) {
    // Handle database errors
    $response = array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
    echo json_encode($response);
}

function createTables($db)
{
    // Create the ApprovedDevice table if it doesn't exist
    $createApprovedTable = 'CREATE TABLE IF NOT EXISTS ApprovedDevice (
        id INT AUTO_INCREMENT PRIMARY KEY,
        deviceName VARCHAR(255) NOT NULL,
        ipAddress VARCHAR(255) NOT NULL,
        deviceType VARCHAR(255) NOT NULL,
        timeApproved TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )';

    $db->exec($createApprovedTable);

    // Create the UnapprovedDevice table if it doesn't exist
    $createUnapprovedTable = 'CREATE TABLE IF NOT EXISTS UnapprovedDevice (
        id INT AUTO_INCREMENT PRIMARY KEY,
        deviceName VARCHAR(255) NOT NULL,
        ipAddress VARCHAR(255) NOT NULL,
        deviceType VARCHAR(255) NOT NULL,
        timeUnapproved TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )';

    $db->exec($createUnapprovedTable);

    // Create the IgnoredDevice table if it doesn't exist
    $createIgnoredTable = 'CREATE TABLE IF NOT EXISTS IgnoredDevice (
        id INT AUTO_INCREMENT PRIMARY KEY,
        deviceName VARCHAR(255) NOT NULL,
        ipAddress VARCHAR(255) NOT NULL,
        deviceType VARCHAR(255) NOT NULL,
        timeIgnored TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )';

    $db->exec($createIgnoredTable);
}

function shouldInsertApproved($deviceType, $deviceName, $ipAddress)
{
    // Example: Insert into ApprovedDevice if the device is a known server
    return ($deviceType === 'Windows Server' || $deviceType === 'Linux Server') && isValidIPAddress($ipAddress);
}

function shouldInsertUnapproved($deviceType, $deviceName)
{
    // Example: Insert into UnapprovedDevice if the device type is unknown or the name contains "Unknown"
    return $deviceType === 'Unknown' || stripos($deviceName, 'Unknown') !== false;
}

function shouldInsertIgnored($deviceType, $deviceName, $ipAddress)
{
    // Example: Insert into IgnoredDevice if any of the fields has an "Unknown" value
    return $deviceType === 'Unknown' || stripos($deviceName, 'Unknown') !== false || !isValidIPAddress($ipAddress);
}

function isValidIPAddress($ipAddress)
{
    // Example: Simple validation for illustration purposes
    return filter_var($ipAddress, FILTER_VALIDATE_IP);
}
?>
