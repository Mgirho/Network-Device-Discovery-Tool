<?php
// variables according to your MySQL setup
$host = 'localhost';
$username = 'root';
$database = 'networkdevice';

try {
    // Connect to MySQL
    $db = new PDO("mysql:host=$host;dbname=$database", $username);
  
    // Fetch data from the device table
    $stmtDevice = $db->query('SELECT * FROM Devices');
    $deviceData = $stmtDevice->fetchAll(PDO::FETCH_ASSOC);

    $deviceJson = json_encode($deviceData);
    
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
    <title>Add Device Data</title>
   
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

<h2>Add Device Data</h2>

    <?php if (!empty($deviceData)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Host Name</th>
                <th>IP Address</th>
                <th>Organization</th>
                <th>Location</th>
                <th>Timezone</th>
                <th>Time Added</th>
            </tr>
            <?php foreach ($deviceData as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['hostName'] ?></td>
                    <td><?= $row['ipAddress'] ?></td>
                    <td><?= $row['orgz'] ?></td>
                    <td><?= $row['loc'] ?></td>
                    <td><?= $row['timeZone'] ?></td>
                    <td><?= $row['timeAdded'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No device data available.</p>
    <?php endif; ?>

</body>
</html>
