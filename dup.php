<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            word-wrap: break-word; /* This allows long words to break and wrap onto the next line */
        }
    </style>
</head>
<body>
<?php
// Initialize variables
$date1 = $session1 = $find_table = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['date'])) {
        $date1 = $_POST['date'];
    }
    if (isset($_POST['session'])) {
        $session1 = $_POST['session'];
    }
    $find_table = $date1 . "_" . $session1;
   
    // Sanitize the common table name by removing any special characters
    $find_table = preg_replace('/[^A-Za-z0-9_]/', '_', $find_table);
}
?>
<form action="" method="post" style="padding-left: 30px;padding-top: 20px;">
    ENTER THE DATE AND SESSION TO VIEW ALLOCATED EXAM HALLS <br><br>
    DATE: <input type="date" id="date" name="date" value="<?php echo $date1; ?>" required style="margin: 10px 30px;">
    SESSION: <select id="session" name="session" required style="margin: 10px 30px;">
        <option value="FN" <?php if ($session1 == "FN") echo "selected"; ?>>FN</option>
        <option value="AN" <?php if ($session1 == "AN") echo "selected"; ?>>AN</option>
    </select>
    <button type="submit" style="margin: 15px; padding: 3px;">GO</button>
</form>

<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Array of database names
$dbnames =  ['a11', 'a12', 'a13', 'b11', 'b12', 'b13', 'c11', 'c12', 'c13']; // Add your database names here

// Output array
$output = [];

// Iterate through each database
foreach ($dbnames as $dbname) {
    // Create a PDO connection for each database
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if the table exists in the current database
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$find_table]);
        $tableExists = $stmt->fetchColumn();
        
        if ($tableExists) {
            // If the table exists, fetch the database name, table name, and column names
            $result = $conn->query("SELECT DATABASE()");
            $databaseName = $result->fetchColumn();
            
            // Add database name, table name, and column names to the output array
            $output[] = [
                'Database' => $databaseName,
                'Table' => $find_table,
                
            ];
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Display the output
if (empty($output)) {
    echo " <br>Table '$find_table' not found in any database.";
} else {
    echo "<table>";
    echo "<tr><th>Database</th><th>Table</th><th>Action</th></tr>";
    foreach ($output as $row) {
        echo "<tr>";
        echo "<td>".$row['Database']."</td>";
        echo "<td>".$row['Table']."</td>"; 
        echo "<td>
                <form action='' method='post' style='display:inline;'>
                    <input type='hidden' name='database' value='".$row['Database']."'>
                    <input type='hidden' name='table' value='".$row['Table']."'>
                    <button type='submit' name='action' value='view'>View</button>
                    <button type='submit' name='action' value='delete'>Delete</button>
                </form>
              </td>";   
        echo "</tr>";
    }
    echo "</table>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $database = $_POST['database'];
    $table = $_POST['table'];
    $action = $_POST['action'];
    
    // Connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Handle different actions
    if ($action === 'view') {
        // View the table row content
        $stmt = $conn->prepare("SELECT * FROM $table");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Table Content: $table</h2>";
        if (empty($rows)) {
            echo "<p>No rows found in the table.</p>";
        } else {
            echo "<table>";
            echo "<tr>";
            foreach ($rows[0] as $column => $value) {
                echo "<th>$column</th>";
            }
            echo "</tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $column => $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    } elseif ($action === 'delete') {
        // Delete the table
        $stmt = $conn->prepare("DROP TABLE IF EXISTS $table");
        $stmt->execute();
        
        echo "<p>Table '$table' in database '$database' has been deleted.</p>";
    }
}
?>
</body>
</html>
