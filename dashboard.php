<?php include 'admin_page.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>
</head>

<body>
    <section class="home">
  <!-- <div class="text">this is my dashboard</div> -->
        <style>
        .container {
            width: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 30vh;
        }
        .button {
            
            padding: 30px;
            background-color:#b515ea;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="button" onclick="redirectToNewPage()">add class of students in database</div>
</div>

<script>
    function redirectToNewPage() {
        window.location.href = 'upload_student.php?from=dashboard';
    }
</script>


    </section>
</body>

</html>



    