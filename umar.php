<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flex Box Color</title>
    <style>
        /* Your CSS styles here */
        body {
            padding-left: 450px;
            /* Add padding to the left side of the body */
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            margin-left: 35px;
            justify-content: flex-start;
        }

        .box {
            width: calc(16.666% - 1rem);
            /* Adjust the width to fit 6 boxes in a row with margin */
            height: 70px;
            width: 70px;
            margin: 0.5rem;
            border: 1px solid #ccc;
            text-align: center;
            line-height: 50px;
            transition: background-color 0.3s ease;
            background-color: #007bff;
            cursor: pointer;
            /* Add cursor pointer for better UX */
            direction: row;
            position: relative; /* Added for absolute positioning of message */
        }

        .box.green {
            background-color: #3CB371;
        }

        .box.yellow {
            background-color: #FFD700;
        }

        .box.red {
            background-color: red;
        }

        .box.selected {
            background-color: #003366;
        }

        .box.green.selected {
            background-color: #006400;
        }

        .box.red.selected {
            background-color: #8B0000;
        }

        .box.yellow.selected {
            background-color: #DAA520;
        }

        .box.selected:hover {
            filter: brightness(80%);
        }

        .box .message {
            display: none;
            position: absolute;
            bottom: -25px; /* Position the message below the box */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            padding: 2px;
            border-radius: 2px;
            color: white;
            z-index: 1;
            white-space: nowrap; /* Ensures the message stays in a single line */
        }

        .box:hover .message {
            display: block; /* Display the message when hovering over the box */
        }

        #numOutput {
            padding-left: 30px;
            /* Adjust the left padding value as needed */
        }

        #conn {
            padding-left: 30px;
        }

        .submit-button {
            padding: 5px;
            margin-left: 350px;
        }
    </style>
</head>

<body>
    <div class="content">
        <?php
        // Retrieve the variables from URL parameters
        include 'test.php';


        // Check if session variables are set
        if (isset($_SESSION['table1'])) {
            $table1 = $_SESSION['table1'];
        } else {
            $table1 = "<span> class1 :Not set</span>";
        }

        if (isset($_SESSION['table2'])) {
            $table2 = $_SESSION['table2'];
        } else {
            $table2 = "<span> class2 :Not set</span>";
        }

        if (isset($_SESSION['common_table'])) {
            $common_table = $_SESSION['common_table'];
        } else {
            $common_table = "<span>Not set</span>";
        }
        echo "<span id=\"conn\"> Date and Session : $common_table</span><br>";
        // Database credentials
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbnames = ['a11', 'a12', 'a13', 'b11', 'b12', 'b13', 'c11', 'c12', 'c13', 'd11', 'd12', 'd13','a21','a22','a23','b21','b22','b23']; // List of your databases
        // Specify your table name here

        // Initialize an array to store the results for each database
        $results = [];

        try {
            foreach ($dbnames as $dbname) {
                // Create a PDO connection for each database
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                // Set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Check if the table exists in the current database
                $tableExistsQuery = "SHOW TABLES LIKE '$common_table'";
                $tableExistsResult = $conn->query($tableExistsQuery);
                $tableExists = $tableExistsResult->rowCount() > 0;

                // Initialize variables
                $rowCount = 0;
                $color = '';
                $message = '';

                if ($tableExists) {
                    // Get the row count of the table
                    $rowCountQuery = "SELECT COUNT(*) AS count FROM $common_table";
                    $rowCountResult = $conn->query($rowCountQuery);
                    $rowCount = $rowCountResult->fetch(PDO::FETCH_ASSOC)['count'];

                    // Determine the color based on the row count
                    if ($rowCount == 25) {
                        $color = 'red';
                    } elseif ($rowCount < 25) {
                        $color = 'yellow';
                        $difference = 25 - $rowCount;
                        $message = "available space = $difference";
                    }
                } else {
                    $color = 'green';
                }

                // Store the result for the current database
                $results[$dbname] = ['color' => $color, 'message' => $message];

                // Close the connection
                $conn = null;
            }
        } catch (PDOException $e) {
            echo "<span id=\"conn\"> Connection failed: " . $e->getMessage() . "</span>";
        } catch (Exception $e) {
            echo "<span id=\"conn\">Error: " . $e->getMessage() . "</span>";
        }
        ?>
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "stdbdemo";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("<span id=\"conn\">Connection failed: " . $conn->connect_error . "</span>");
        }

        // Check if table1 exists
        $result1 = $conn->query("SHOW TABLES LIKE '$table1'");

        if ($result1->num_rows > 0) {
            // Table 1 exists, count the number of rows
            $sql1 = "SELECT COUNT(*) AS count FROM $table1";
            $result1 = $conn->query($sql1);
            $row1 = $result1->fetch_assoc();
            echo "<span id=\"conn\">class 1 count:  " . $row1['count'] . "</span><br>";
        } else {
            // Table 1 does not exist
            echo "<span id=\"conn\">class 1 not found</span><br>";
        }

        // Check if table2 exists
        $result2 = $conn->query("SHOW TABLES LIKE '$table2'");
        if ($result2->num_rows > 0) {
            // Table 2 exists, count the number of rows
            $sql2 = "SELECT COUNT(*) AS count FROM $table2";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            echo "<span id=\"conn\">class 2 count:  " . $row2['count'] . "</span><br>";
        } else {
            // Table 2 does not exist
            echo "<span id=\"conn\">class 2 not found</span><br>";
        }
        $total = $row1['count'] + $row2['count'];
        echo "<span id=\"conn\">TOTAL STUDENTS :  " . $total . "</span><br>";
        echo "<span id=\"conn\"> (note: if you wish to select yellow box, then make sure you select it at first)</span><br><br>";
        $conn->close();

        ?> <!-- end -->
      
        <span id="numOutput"></span>
        <!-- Render database boxes with colors and messages -->
        <div class="container">
            <?php foreach ($results as $dbname => $result) : ?>
                <div class="box <?php echo $result['color']; ?>" data-dbname="<?php echo $dbname; ?>">
                    <?php echo "<span>$dbname</span>"; ?>
                    <?php if (!empty($result['message'])) : ?>
                        <div class="message"><?php echo "<span>" . $result['message'] . "</span>"; ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>




        <!-- Output area for displaying num -->

        <!-- Your form and submit button -->
        <form id="myForm" action="test2.php" method="post">
            <input type="hidden" id="selectedDatabasesInput" name="selectedDatabases">
        </form>
        <br><br>
        <button type="button" class="submit-button" onclick="submitForm()">Submit</button>

        <!-- JavaScript code -->
        <script>
            let selectedDatabases = [];
            let num;
            let n=0;
            function handleBoxClick(event) {
                const dbName = event.target.dataset.dbname; // Get database name from data attribute
                const isSelected = event.target.classList.contains('selected'); // Check if box is selected
                const numBoxes = document.querySelectorAll('.box.selected').length;

                // Check if the clicked box is red
                if (event.target.classList.contains('red')) {
                    // If it's red, return without doing anything
                    return;
                }

                // Check if the clicked box is yellow
                const isYellow = event.target.classList.contains('yellow');

                // Calculate num based on the color of the clicked box
                let tot;
                if (isYellow) {
                    const rowCount1 = <?php echo isset($row1['count']) ? $row1['count'] : 0; ?>;
                    const rowCount2 = <?php echo isset($row2['count']) ? $row2['count'] : 0; ?>;
                    const difference = <?php echo isset($difference) ? $difference : 0; ?>;
                    tot = rowCount1 + rowCount2 ;
                    tot = tot - difference;
                    num = Math.ceil((tot / 25) + 1);
                    n=n+1;
                } 
                if (n===0)
               {
                    const rowCount1 = <?php echo isset($row1['count']) ? $row1['count'] : 0; ?>;
                    const rowCount2 = <?php echo isset($row2['count']) ? $row2['count'] : 0; ?>;
                    tot = rowCount1 + rowCount2;
                    num = Math.ceil(tot / 25);
                }

                // Display num in the output area
                document.getElementById("numOutput").innerHTML = "<span>Total exam halls required: </span>" + num;

                if (!isSelected && numBoxes >= num) {
                    // If box is not selected and the maximum number of boxes are already selected, do nothing
                    alert("You can only select " + num + " boxes.");
                    return;
                }

                // Toggle 'selected' class for styling
                event.target.classList.toggle('selected');

                // Update selectedDatabases array
                const index = selectedDatabases.indexOf(dbName);
                if (!isSelected && index === -1) {
                    selectedDatabases.push(dbName);
                } else if (isSelected && index !== -1) {
                    selectedDatabases.splice(index, 1);
                }

                // Output selected database names to console
                console.log(selectedDatabases);

                // Update hidden input field with comma-separated list
                document.getElementById("selectedDatabasesInput").value = selectedDatabases.join(",");
            }

            // Add click event listeners to all database boxes
            document.querySelectorAll('.box').forEach(box => {
                box.addEventListener('click', handleBoxClick);
            });

            // Function to submit the form
            function submitForm() {
                const numBoxes = document.querySelectorAll('.box.selected').length;

                if (numBoxes === num) {
                    // If the number of selected boxes equals num, submit the form
                    document.getElementById("myForm").submit();
                } else {
                    // If not, display a message
                    alert("Please select " + num + " boxes.");
                }
            }
        </script>
    </div>
</body>

</html>