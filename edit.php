<?php
include  "config.php";
include "functionality.php";
$error = [];
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
}
$id = $_GET['id']; //id wise data anbo get diye
$sql =  "SELECT * FROM users WHERE id=$id"; //sql kortesi jekhane id
$result = mysqli_query($conn,$sql); //query chalaitesi
$value = mysqli_fetch_assoc($result); // queryr value array diye fetch kortesi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Escape special characters in form inputs to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    if (isset($_FILES)) { //file ta jodi upload dei
        $up  = image($_FILES);
        if ($up) {
            $sql1 = "UPDATE users SET  email='$email', password='$password',fname='$firstName', lname='$lastName',address='$address',city='$city',file = '$up'  WHERE id=$id";
        } else {
            $sql1 = "UPDATE users SET  email='$email', password='$password',fname='$firstName', lname='$lastName',address='$address',city='$city' WHERE id=$id";

        }
    } else {
        $sql1 = "UPDATE users SET  email='$email', password='$password',fname='$firstName', lname='$lastName',address='$address',city='$city' WHERE id=$id";

    }


    //Validation
    if (empty($email)) $error[] = "Email is required"; // Check if email is provided
    if (empty($password)) $error[] = "Password is required"; // Check if password is provided
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error[] = "Invalid email format"; // Validate email format

    // If no errors, proceed to insert the user data into the database
    if (empty($error)) {
      
        // Execute the prepared statement
       if (mysqli_query($conn, $sql1)){
            
             header("Location: index.php");
            
            exit(); // Stop further execution
       }else{
               // If there's an error, add it to the error array
               $error[] = "Error: " . mysqli_error($conn);
           }
        }
    }
    
?>



<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <!-- Link to Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create New User</h2>
        <!-- Display errors if there are any -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php foreach ($error as $msg) echo "<div>$msg</div>"; // Display each error message ?>
            </div>
        <?php endif; ?>
        
        <!-- User creation form -->
        <form  method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <!-- Email input -->
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $value['email'] ?>" class="form-control" required>
                </div>
                <!-- Password input -->
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" value="<?=$value['password']?>" class="form-control" required>
                </div>
                <!-- First Name input -->
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?=$value['fname']?>" class="form-control" required>
                </div>
                <!-- Last Name input -->
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?=$value['lname']?>" class="form-control" required>
                </div>
                <!-- Address input -->
                <div class="col-12">
                    <label>Address</label>
                    <input type="text" name="address" value="<?=$value['address']?>" class="form-control">
                </div>
                <!-- City input -->
                <div class="col-md-6">
                    <label>City</label>
                    <input type="text" name="city" value="<?=$value['city']?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Choose file</label>
                    <input
                        type="file"
                        class="form-control"
                        name="file"
                        
                        id=""
                        placeholder=""
                        aria-describedby="fileHelpId" /> <br>
                </div>
                <!-- Submit button -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <!-- Cancel button -->
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>