<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    if (isset($_POST['adminname'])) {
        $adminName = $_POST["adminname"];
    }
    if (isset($_POST['stpassword'])) {
        $password = $_POST["stpassword"];
    }
    if (isset($_POST['re_entered_password'])) {
        $reEnteredPassword = $_POST["re_entered_password"];
    }

    // Check if passwords match
    if ($password === $reEnteredPassword) {
        // Passwords match, proceed to store in the database

        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $dbpassword = ""; // Please replace with your database password
        $dbname = "admin"; // Your database name

        // Create connection
        $conn = new mysqli($servername, $username, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to insert data into adminlogin table
        $sql = "INSERT INTO adminlogin (`username`, `password`) VALUES ('$adminName', '$password')";

        // Execute SQL statement
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close database connection
        $conn->close();
    } else {
        // Passwords do not match, display an error message
        echo "Error: Passwords do not match";
    }
}
?>
