<?php
//variables according to your MySQL setup
$host = 'localhost';
$username = 'root';
$database = 'networkdevice';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$database", $username);
  
    // Fetch data from the ApprovedDevice table
    $stmtApprovedDevice = $db->query('SELECT * FROM ApprovedDevice');
    $approvedDeviceData = $stmtApprovedDevice->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data from the UnapprovedDevice table
    $stmtUnapprovedDevice = $db->query('SELECT * FROM UnapprovedDevice');
    $unapprovedDeviceData = $stmtUnapprovedDevice->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data from the IgnoredDevice table
    $stmtIgnoredDevice = $db->query('SELECT * FROM IgnoredDevice');
    $ignoredDeviceData = $stmtIgnoredDevice->fetchAll(PDO::FETCH_ASSOC);

    $approvedDeviceJson = json_encode($approvedDeviceData);
    $unapprovedDeviceJson = json_encode($unapprovedDeviceData);
    $ignoredDeviceJson = json_encode($ignoredDeviceData);
    
    // Close the database connection
    $db = null;
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
    <title>Display Form Data</title>
   
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

    <h2>Approved Device Data</h2>

    <?php if (!empty($approvedDeviceData)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Device Name</th>
                <th>IP Address</th>
                <th>Device Type</th>
                <th>Time Approved</th>
            </tr>
            <?php foreach ($approvedDeviceData as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['deviceName'] ?></td>
                    <td><?= $row['ipAddress'] ?></td>
                    <td><?= $row['deviceType'] ?></td>
                    <td><?= $row['timeApproved'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No approved device data available.</p>
    <?php endif; ?>

    <h2>Unapproved Device Data</h2>

    <?php if (!empty($unapprovedDeviceData)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Device Name</th>
                <th>IP Address</th>
                <th>Device Type</th>
                <th>Time Unapproved</th>
            </tr>
            <?php foreach ($unapprovedDeviceData as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['deviceName'] ?></td>
                    <td><?= $row['ipAddress'] ?></td>
                    <td><?= $row['deviceType'] ?></td>
                    <td><?= $row['timeUnapproved'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No unapproved device data available.</p>
    <?php endif; ?>

    <h2>Ignored Device Data</h2>

    <?php if (!empty($ignoredDeviceData)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Device Name</th>
                <th>IP Address</th>
                <th>Device Type</th>
                <th>Time Ignored</th>
            </tr>
            <?php foreach ($ignoredDeviceData as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['deviceName'] ?></td>
                    <td><?= $row['ipAddress'] ?></td>
                    <td><?= $row['deviceType'] ?></td>
                    <td><?= $row['timeIgnored'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No ignored device data available.</p>
    <?php endif; ?>

</body>
</html>
