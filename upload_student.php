<?php include 'admin_page.php'; ?>
<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

// Replace with your actual database credentials
$servername ='localhost';
$username = 'root';
$password = '';
$dbname = 'stdbdemo';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        try {
            // Create a PDO connection
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Load the Excel file
            $spreadsheet = IOFactory::load($_FILES["file"]["tmp_name"]);

            // Get the first worksheet in the Excel file
            $worksheet = $spreadsheet->getActiveSheet();

            // Get the original file name
            $originalFileName = $_FILES["file"]["name"];
            
            // Extract the file name without extension
            $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);

            // Use the file name without extension as the table name
            $tableName = $fileNameWithoutExtension;

            // Check if the table already exists
            $tableExistsQuery = "SHOW TABLES LIKE '$tableName'";
            $tableExistsResult = $conn->query($tableExistsQuery);
            if ($tableExistsResult->rowCount() > 0) {
                echo '<script>alert("Table $tableName already exists. Data not inserted.");</script>';
            } else {
                // Create a table dynamically
                $createTableQuery = "CREATE TABLE $tableName (";
                $firstRowSkipped = false;
                foreach ($worksheet->getRowIterator() as $row) {
                    if (!$firstRowSkipped) {
                        $firstRowSkipped = true;
                        foreach ($row->getCellIterator() as $cell) {
                            $createTableQuery .= "`" . $cell->getValue() . "` VARCHAR(255), ";
                        }
                        $createTableQuery = rtrim($createTableQuery, ', ');
                        $createTableQuery .= ")";
                        $conn->exec($createTableQuery);
                        continue; // Skip processing the first row further
                    }
                    $rowData = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    $insertQuery = "INSERT INTO $tableName VALUES ('" . implode("', '", $rowData) . "')";
                    $conn->exec($insertQuery);
                }

                echo '<script>alert("Data inserted successfully into table ' . $tableName . '");</script>';
            }
        } catch (PDOException $e) {
          
            echo '<script>alert("Connection failed: ' . $e->getMessage() . '");</script>';

        } catch (Exception $e) {
         
            echo '<script>alert("Error : ' . $e->getMessage() . '");</script>';

        } finally {
            // Close the database connection
            $conn = null;
        }
    } else {
       
        echo '<script>alert("No file uploaded or an error occurred.");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <title>Excel File Upload</title>
    <style>
        .container {
            width: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 30vh;
            margin-left: 450px;
            margin-top: 10px;
        }
        .button {
            
            padding: 30px;
            background-color:#FFFFFF;
            color:#707070;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
       
    </style>
  
</head>
<body>
<section class="home">
        <div class="text"></div>
         <p>
         <div class="container">
    <div class="button">note: <br>1. the .xlsx file must be saved in the format with lower case "class" "_" "batch"  example : bsc_cs_a_2020.<br>
    2. the first row in the excel file is considered as heading which is exactly saved in the database table heading. <br>
    3. there should be only 4 headings namely "roll_number", "register_number", "name" and "department". <br>
    4. these headings must be in lower case and additional headings will leads in error. <br>
    5. the headings must be in order as mentioned above. </div>
</div>
<form action="upload_student.php" method="post" enctype="multipart/form-data" style="padding-left: 130px;padding-top: 210px;">
    <label for="file">Choose Excel file:</label>
    <input type="file" name="file" id="file" accept=".xlsx">
    <button type="submit" >Upload</button>
</form>
         </p>
</section>
</body>
</html>
