<?php
// variables according to your MySQL setup
$host = 'localhost';
$username = 'root';
$database = 'networkdevice';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$database", $username);
  
    // Fetch the most recent network devices table name
    $stmtRecentTable = $db->query('SHOW TABLES LIKE "NetworkDevices_%"');
    $recentTable = $stmtRecentTable->fetchColumn();

    if ($recentTable) {
        // Fetch data from the most recent network devices table
        $stmtDevice = $db->query("SELECT * FROM $recentTable");
        $deviceData = $stmtDevice->fetchAll(PDO::FETCH_ASSOC);

        $deviceJson = json_encode($deviceData);

        // Close the database connection
        $db = null;
    } else {
        // No recent network devices table found
        $deviceData = [];
    }
} catch (PDOException $e) {
    // Handle database errors
    echo 'Database error: ' . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Devices</title>
   
    <!-- Add this line to include Chart.js from a CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        background-image: url('bg4.jpg');
        background-size: cover;
        background-position: center;
        margin: 0;
        height: 100vh;
    }
    </style>
</head>
<body>

<h2>Latest Network Devices</h2>

    <?php if (!empty($deviceData)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Device Name</th>
                <th>IP Address</th>
                <th>Host Name</th>
                <th>City</th>
                <th>Region</th>
                <th>Country</th>
                <th>Location</th>
                <th>Organization</th>
                <th>Postal</th>
                <th>Timezone</th>
                <th>Time Added</th>
            </tr>
            <?php foreach ($deviceData as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['deviceName'] ?></td>
                    <td><?= $row['ipAddress'] ?></td>
                    <td><?= $row['hostname'] ?></td>
                    <td><?= $row['city'] ?></td>
                    <td><?= $row['region'] ?></td>
                    <td><?= $row['country'] ?></td>
                    <td><?= $row['loc'] ?></td>
                    <td><?= $row['org'] ?></td>
                    <td><?= $row['postal'] ?></td>
                    <td><?= $row['timezone'] ?></td>
                    <td><?= $row['timeAdded'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No network devices data available.</p>
    <?php endif; ?>

</body>
</html>
