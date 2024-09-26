<?php
if($_SERVER['REQUEST_METHOD']=='POST')
{
   include 'connect.php';
   $username=$_POST['username'];
   $adpassword=$_POST['adpassword'];

   $sql="Select * from `adminlogin` where username='$username' and password='$adpassword'";
   $result=mysqli_query($con,$sql);
   if($result)
   {
      $num=mysqli_num_rows($result);
      if($num>0){
       session_start();
       $_SESSION['username']=$username;
       $_SESSION['password']=$adpassword;
       header('location:dashboard.php');
      }
   }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Seating Arrangement System</title>
   <link rel="stylesheet" href="css/style.css"> 
</head>

<body>
   <div class="container" id="container">
      <div class="form-container student-login-container">
         <form action="connect1.php" method="post">
            <h1>ADD ADMIN</h1>
            <input type="text" placeholder="Admin Name" name="adminname" />
            <input type="password" placeholder="Password" name="stpassword"/>
            <input type="password" placeholder="Re-enter Password" name="re_entered_password"/>
            <button type="submit">ADD ADMIN</button>
         </form>
      </div>
      <div class="form-container admin-login-container">
         <form action="index.php" method="post">
            <h1>ADMIN LOGIN</h1>
            <input type="text" placeholder="Admin Name" name="username"/>
            <input type="password" placeholder="Password" name="adpassword"/>
            <button type="submit" >LOGIN</button>
         </form>
      </div>
      <div class="overlay-container">
         <div class="overlay">
            <div class="overlay-panel overlay-left">
               <h1>Welcome Back Admin!</h1>
               <p>Enter Admin Name ,And Password To Add Admin In Database!</p>
               <button class="ghost" id="adminLogin">Admin Login</button>
            </div>
            <div class="overlay-panel overlay-right">
               <h1>Hello, Admin!</h1>
               <p>If You Like To Add Other Admin, Then Click The Button Given Below</p>
               <button class="ghost" id="studentLogin">Add Admin</button>
            </div>
         </div>
      </div>
   </div>


   <script src="script.js"></script>


</body>

</html>