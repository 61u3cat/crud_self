<?php
include 'config.php';
session_start();


if (isset($_SESSION["email"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
            <div class="row">

                <h3 class="heading">User Login</h3>
                <!-- Form Start -->
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="" required>
                    </div>
                    <div>
                        <br>
                        <input type="submit" name="login" class="btn btn-primary mb-3" value="login" />
                        <a href="create.php" class="btn btn-success mb-3">Signup</a>
                    </div>
                </form>
                <?php
                if (isset($_POST['login'])) {

                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    //$password = password_hash($_POST['password'], PASSWORD_BCRYPT);


                    $sql = "SELECT * FROM users WHERE email  = '{$email}'";
                    $result = mysqli_query($conn, $sql) or die("query failed");
                    if (mysqli_num_rows($result)) {
                        $row = mysqli_fetch_assoc($result);
                        $hashUser = $row['password'];
                      } else {
                          echo 'email not found';
                         exit;
                          // header("Location: login.php");
                     }
                    $verify = password_verify($_POST['password'], $hashUser);
                    if ($verify) {
                        $_SESSION["email"] = $row['email'];
                        header("Location: index.php");
                    } else {
                        if(!$verify){
                            echo 'Password vul';
                        }else{
                            echo 'Email vul';
                        }
                        //echo 'password & email doesnt match';
                    }
                }
                ?>
                <!-- /Form  End -->
            </div>
        </div>
    </div>

</body>

</html>