<?php
$HOSTNAME='localhost';
$USERNAME='root';
$PASSWORD=' ';
$DATABASE='admin';

$con=mysqli_connect($HOSTNAME,$USERNAME,$PASSWORD,$DATABASE);
if(!$con)
{
    die("Connection failed: " . mysqli_connect_error());
}
?>
