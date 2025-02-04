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
                // Define the number of records per page
                $limit = 3;

                // Get the current page number from the URL, default is 1
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
       
                // Calculate the starting record of the current page
                $offset = ($page - 1) * $limit;
       
                // SQL query to select all users who are not deleted, with pagination
                $sql = "SELECT * FROM users WHERE delete_at IS NULL LIMIT {$offset}, {$limit}";

                // Execute the query
                $result = mysqli_query($conn, $sql);

                // Check if there are any records returned
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each user
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
        <?php
         
        // Query to count the total number of users (excluding deleted ones)
        $sql1 = "SELECT COUNT(*) AS total FROM users WHERE delete_at IS NULL";
        $result1 = mysqli_query($conn, $sql1) or die('Query failed');

        // Fetch the total number of records
        $row = mysqli_fetch_assoc($result1);
        $total_records = $row['total'];

        // Calculate the total number of pages
        $total_pages = ceil($total_records / $limit);

        // Display pagination if there's more than one page
        if ($total_pages > 1) {
            echo '<nav><ul class="pagination justify-content-center">';

            // Previous page link
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page - 1) . '">Prev</a></li>';
            }

            // Page number links
            for ($i = 1; $i <= $total_pages; $i++) {
                // Highlight the active page
                $active = ($i == $page) ? 'active' : '';
                echo '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page=' . $i . '">' . $i . '</a></li>';
            }

            // Next page link
            if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page + 1) . '">Next</a></li>';
            }

            echo '</ul></nav>';
        }
        ?>
    </div>
</body>
</html>
