<?php
// Include the database configuration file
include 'config.php';

// Start a new session or resume an existing one
session_start();
// Check if the user's email is set in the session (i.e., if they're logged in)
if (!isset($_SESSION["email"])) {
    // If not, redirect the user to the login page
    header("Location: login.php");
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Include Bootstrap CSS from CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Main container with a top margin -->
    <div class="container mt-5">
        <h2>User Management</h2>
        <!-- Button to add a new user -->
        <a href="create.php" class="btn btn-success mb-3">Add New User</a>
        <!-- Button to log out -->
        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
        <!-- Table to display users -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <!-- Table headers -->
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
                $sql = "SELECT * FROM users WHERE delete_at IS NULL";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <!-- Display user ID -->
                            <td><?= $row['id'] ?></td>
                            <!-- Display user's full name -->
                            <td><?= $row['fname'] . ' ' . $row['lname'] ?></td>
                            <!-- Display user email -->
                            <td><?= $row['email'] ?></td>
                            <!-- Display user address -->
                            <td><?= $row['address'] ?></td>
                            <!-- Display user file (e.g., profile image) -->
                            <td><img src="upload/<?= $row['file'] ?>" alt="" width="100px"></td>
                            <!-- Display user city -->
                            <td><?= $row['city'] ?></td>
                            <!-- Action buttons for editing and deleting -->
                            <td>
                                <!-- Edit button linking to edit page with user ID -->
                                <a href='edit.php?id=<?= $row['id'] ?>' class='btn btn-primary'>Edit</a>
                                <!-- Delete button with confirmation prompt -->
                                <a href='delete.php?id=<?= $row['id'] ?>' class='btn btn-danger' onclick='return confirm("Are you sure?")'>Delete</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    // If no users are found, display a message
                    echo "<tr><td colspan='7'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('.table');
    </script>
</body>

</html>