<?php

include "config.php";
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
}
$id = $_GET['id'];
$date = date("Y-m-d");
$sql = "UPDATE users set delete_at = '{$date}' WHERE id=$id";
$result = mysqli_query($conn, $sql);


$sql = "select * from users WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc(result: $result);
if($row && !empty($row['file'])){
    $filepath = 'upload/'. $row['file'];
    if(file_exists($filepath)){
        unlink($filepath);
    }
    
}

header("Location: index.php");
