
<?php include 'admin_page.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date and Session Form</title>
</head>
<body>
<section class="home">
       
        

<?php
// Start the session to use session variables

/* session_start(); */

$table1 = "";
$table2 = "";
$date = "";
$session = "";
$common_table = ""; // Initialize the common table variable

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['date'])) {
        $date = $_POST['date'];
    }
    if (isset($_POST['session'])) {
        $session = $_POST['session'];
    }
    if (isset($_POST['batch1']) && isset($_POST['class1'])) {
        $batch1 = $_POST['batch1'];
        $class1 = $_POST['class1'];
        $table1 = $class1 . "_" . $batch1;
    }
    if (isset($_POST['batch2']) && isset($_POST['class2'])) {
        $batch2 = $_POST['batch2'];
        $class2 = $_POST['class2'];
        $table2 = $class2 . "_" . $batch2;
    }

    // Create the common table name using date and session
    $common_table = $date . "_" . $session;
   
    // Sanitize the common table name by removing any special characters
    $common_table = preg_replace('/[^A-Za-z0-9_]/', '_', $common_table);

    // Store the values in session variables
    $_SESSION['table1'] = $table1;
    $_SESSION['table2'] = $table2;
    $_SESSION['common_table'] = $common_table;

    // Redirect to umar.php
    header("Location: umar.php");
    exit();   // Stop further execution of PHP script
}
?>
<form action="test.php" method="post" style="padding-left: 30px;padding-top: 20px;">
    ENTER THE DATE AND SESSION TO ALLOCATE EXAM HALLS <br><br>
    DATE: <input type="date" id="date" name="date" required style="margin: 10px 30px;">
    SESSION: <select id="session" name="session" required style="margin: 10px 30px;">
        <option value="FN">FN</option>
        <option value="AN">AN</option>
    </select>
    <br>
    SELECT BATCH AND CLASS FOR THE CLASS 1 
    <select name="batch1" id="batch1" style="margin: 10px 30px;">
        <option value="2020"> 2020</option>
        <option value="2021"> 2021</option>
        <option value="2022"> 2022</option>
        <option value="2023"> 2023</option>
        <option value="2024"> 2024</option>
    </select>

    <select name="class1" id="class1" style="margin: 10px 5px;">
        <option value="bsc_cs_a"> bsc.cs-a</option>
        <option value="bsc_cs_b"> bsc.cs-b</option>
        <option value="bsc_cs_c"> bsc.cs-c</option>
        <option value="bsc_it_a"> bsc.it-a</option>
        <option value="bsc_it_b"> bsc.it-b </option>
        <option value="bsc_it_c"> bsc.it-c </option>
    </select><br>
    SELECT BATCH AND CLASS FOR THE CLASS 2 
    <select name="batch2" id="batch2" style="margin: 10px 30px;">
        <option value="2020"> 2020</option>
        <option value="2021"> 2021</option>
        <option value="2022"> 2022</option>
        <option value="2023"> 2023</option>
        <option value="2024"> 2024</option>
    </select>

    <select name="class2" id="class2" style="margin: 10px 5px;">
        <option value="bsc_cs_a"> bsc.cs-a</option>
        <option value="bsc_cs_b"> bsc.cs-b</option>
        <option value="bsc_cs_c"> bsc.cs-c</option>
        <option value="bsc_it_a"> bsc.it-a</option>
        <option value="bsc_it_b">bsc.it-b </option>
        <option value="bsc_it_c">bsc.it-c </option>
    </select>
    <button type="submit" style="margin: 15px; padding: 3px;">GO</button>
</form>



</body>
</html>
