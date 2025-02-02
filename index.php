<?php include 'config.php';
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>User Management</h2>
        <a href="create.php" class="btn btn-success mb-3">Add New User</a>
        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>File</th>
                    <th>City</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 4;
                if(isset($_GET['page'])){
                    $page = $_GET['page'];
                }else{
                    $page = 1;
                }
                $offset = ($page-1) * $limit;
            
                $sql = "SELECT * FROM users where delete_at is null LIMIT {$offset}, {$limit}";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result)  > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>


                        <tr class="">
                            <td><?= $row['id']?></td>
                            <td><?= $row['fname'] . ' ' . $row['lname'] ?> </td>
                            <td><?= $row['email']?></td>
                            <td><?= $row['address']?></td>
                            <td scope="row"><img src="upload/<?= $row['file'] ?>" alt="" width="100px"></td>
                            <td><?= $row['city']?></td>
                            <td>
                                <a href='edit.php?id=<?= $row['id'] ?>' class='btn btn-primary'>Edit</a>
                                <a href='delete.php?id=<?= $row['id'] ?>' class='btn btn-danger' onclick='return confirm("Are you sure?")'>Delete</a>
                            </td>
                        </tr>
                <?php
                    }
                    

                } else {
                    echo "<tr><td colspan='6'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
        $sql1 = "SELECT * FROM users";
        $result1 = mysqli_query($conn, $sql1) or die('query failed');
                if (mysqli_num_rows($result1) > 0) {
                    $total_records = mysqli_num_rows($result1);

                    // Calculate total pages
                    $total_page = ceil($total_records / $limit); 
                    echo '<nav><ul class="pagination justify-content-center">';
                    if ($page > 1) {
                        // Link to the previous page
                        echo '<li class = "page-item"><a class="page-link" href="index.php?page='.($page - 1).'">Prev</a></li>';
                    }

                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i == $page) {
                            $active = "active";
                        } else {
                            $active = '';
                        }
                        // Links to page numbers
                        echo '<li class = "page-item"' . $active . '"><a class="page-link" href = "index.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        // Link to the next page
                        echo '<li class = "page-item"><a class="page-link" href="index.php?page='.($page + 1).'"> Next</a></li>';
                    }
                }
        ?>
    </div>
</body>

</html>