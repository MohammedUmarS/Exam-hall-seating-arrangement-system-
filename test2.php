<?php
session_start(); // Start the session

// Database connection parameters
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'stdbdemo'; // Change this to your database name if different

try {
    // Connect to the "stdbdemo" database
    $conn_std = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $conn_std->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if session variables are set
    $table1 = isset($_SESSION['table1']) ? $_SESSION['table1'] : "Not set";
    $table2 = isset($_SESSION['table2']) ? $_SESSION['table2'] : "Not set";
    $common_table = isset($_SESSION['common_table']) ? $_SESSION['common_table'] : "Not set";

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve and store the form data in session variables
        $_SESSION['selectedDatabases'] = explode(',', $_POST['selectedDatabases']);
        
    }

    // Retrieve the values from session variables
    $selectedDatabases = isset($_SESSION['selectedDatabases']) ? $_SESSION['selectedDatabases'] : [];
   
    $dupTable1 = 'duplicates_' . $table1; // New table name

    // Create a new table $dupTable1
    $createTableQuery = "CREATE TABLE IF NOT EXISTS `$dupTable1` LIKE `$table1`";
    $conn_std->exec($createTableQuery);

    // Insert data from $table1 into $dupTable1
    $insertDataQuery = "INSERT INTO `$dupTable1` SELECT * FROM `$table1`";
    $conn_std->exec($insertDataQuery);

    $totalOffset = 0; // Track the total offset
    $totalOffset1 = 0;
    // Loop through each selected database
    foreach ($selectedDatabases as $key => $dbname) {
        $conn_db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn_db->prepare("SHOW TABLES LIKE '$common_table'");
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            // Calculate the number of rows needed to fill up to 25
            $stmt = $conn_db->prepare("SELECT COUNT(*) AS count FROM `$common_table`");
            $stmt->execute();
            $rowCount = $stmt->fetchColumn();
            $count = 25 - $rowCount;

            // Insert $count rows from $table1 into $common_table
            $stmt = $conn_std->prepare("SELECT * FROM $dupTable1 LIMIT $count");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $values = "'" . implode("','", $row) . "'";
                $insert_sql = "INSERT INTO `$common_table` VALUES ($values)";
                $conn_db->exec($insert_sql);
            }
            $deleteDataQuery = "DELETE FROM `$dupTable1` LIMIT $count";
            $conn_std->exec($deleteDataQuery);
        } else {
            // Create the common table if it does not exist
            $create_table_sql = "CREATE TABLE IF NOT EXISTS `$common_table` (
                `roll_number` varchar(15),
                `register_number` varchar(15),
                `name` varchar(15),`department` varchar(15)
            )";
            $conn_db->exec($create_table_sql);

            // Calculate the offset for this database
            $offset = $totalOffset;

            // Fetch 12 rows starting from the calculated offset from $dupTable1
            $stmt = $conn_std->prepare("SELECT * FROM $dupTable1 LIMIT 12 OFFSET $offset");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Insert the fetched rows into the corresponding $common_table
            foreach ($rows as $row) {
                $roll_number = $row['roll_number'];
                $register_number = $row['register_number'];
                $name = $row['name'];
                $department = $row['department'];
                $insert_sql = "INSERT INTO `$common_table` (roll_number, register_number, name, department) VALUES ('$roll_number', '$register_number', '$name', '$department')";
                $conn_db->exec($insert_sql);
            }

            // Update the total offset for the next iteration
            $totalOffset += 12;
            $offset1 = $totalOffset1;
            $stmt = $conn_std->prepare("SELECT * FROM $table2 LIMIT 13 OFFSET $offset1");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Insert the fetched rows into the corresponding $common_table
            foreach ($rows as $row) {
                $roll_number = $row['roll_number'];
                $register_number = $row['register_number'];
                $name = $row['name'];
                $department = $row['department'];
                $insert_sql = "INSERT INTO `$common_table` (roll_number, register_number, name, department) VALUES ('$roll_number', '$register_number', '$name', '$department')";
                $conn_db->exec($insert_sql);
            }
            $totalOffset1 += 13;
        }
    }
    $dropTableQuery = "DROP TABLE IF EXISTS `$dupTable1`";
    $conn_std->exec($dropTableQuery);
    echo "Common table '$common_table' created and data inserted successfully in all databases.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
