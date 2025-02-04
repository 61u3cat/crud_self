<?php
// Include the configuration file which connects to the database
include 'config.php';
include 'functionality.php';
session_start();


$error = []; // Initialize an array to store any errors

// Check if the form has been submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape special characters in form inputs to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);


    $checkemail = "SELECT email FROM users WHERE email= '$email'";
    $result = mysqli_query($conn, $checkemail);
    if (mysqli_num_rows($result) > 0) {
        $error[] =  "Email already exists,try another";
    } else {
    
        if (!empty($_FILES['file']['name'])) { //file ta jodi upload dei
            $up  = image($_FILES);
            if ($up) {
                $sql = "INSERT INTO users (email, password, fname, lname, address, file, city)
                VALUES ('$email','$password','$firstName','$lastName','$address','$up','$city')";
              }else {
                 $sql = "INSERT INTO users (email, password, fname, lname, address, city)
                VALUES ('$email','$password','$firstName','$lastName','$address','$city')";
             }
        } else {
            $sql = "INSERT INTO users (email, password, fname, lname, address, city)
                VALUES ('$email','$password','$firstName','$lastName','$address','$city')";
        }
        mysqli_query($conn, $sql);
        header("Location: login.php");
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
                <?php foreach ($error as $msg) echo "<div>$msg</div>"; // Display each error message 
                ?>
            </div>
        <?php endif; ?>

        <!-- User creation form -->
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <!-- Email input -->
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <!-- Password input -->
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <!-- First Name input -->
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <!-- Last Name input -->
                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
                <!-- Address input -->
                <div class="col-12">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <!-- City input -->
                <div class="col-md-6">
                    <label>City</label>
                    <input type="text" name="city" class="form-control">
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
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <!-- Cancel button -->
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>