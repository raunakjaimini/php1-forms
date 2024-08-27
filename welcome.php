<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('db.php');

// Fetch all users from the database
$sql = "SELECT image, id, firstname, lastname, email, mobile_number, dob FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* Dark theme and responsive styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2c2c2c; /* Darker background for consistency */
            color: #e0e0e0; /* Light text color for contrast */
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f5f5f5;
            font-size: 24px;
        }
        table {
            width: 100%;
            max-width: 1200px;
            border-collapse: separate;
            border-spacing: 0 10px; /* Add space between rows */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background-color: #3a3a3a; /* Darker background for table */
        }
        table th, table td {
            border: 1px solid #555;
            padding: 15px; /* Increase padding for more space */
            text-align: left;
        }
        table th {
            background-color: #444; /* Darker shade for table header */
            color: #f5f5f5;
        }
        tr:nth-child(even) {
            background-color: #3a3a3a; /* Alternate row color for better readability */
        }
        tr:hover {
            background-color: #555;
        }
        .profile-img {
            max-width: 60px; /* Increase size for profile image */
            max-height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .action-btn {
            padding: 8px 16px;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            margin-right: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .action-btn.edit {
            background-color: #1e88e5; /* Blue for edit button */
        }
        .action-btn.delete {
            background-color: #e53935; /* Red for delete button */
        }
        .action-btn:hover {
            transform: scale(1.05);
        }
        .logout-btn {
            padding: 10px 20px;
            background-color: #444;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 16px;
        }
        .logout-btn:hover {
            background-color: #333;
            transform: scale(1.05);
        }
        .profile-link {
            color: #1e88e5;
            text-decoration: none;
        }
        .profile-link:hover {
            text-decoration: underline;
        }
        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }
            .logout-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <h2>Welcome <a href="profile.php" class="profile-link"><?php echo htmlspecialchars($_SESSION['email']); ?></a>!</h2>
    
    <table>
        <thead>
            <tr>
                <th>Profile Image</th>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Mobile Number </th>
                <th>Date of Birth </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";

                    $profileImage = !empty($row['image']) ? "uploads/" . htmlspecialchars($row['image']) : "uploads/default.png";
                    echo "<td><img src='" . $profileImage . "' alt='Profile Image' class='profile-img'></td>";

                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['mobile_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                    echo "<td>";
                    echo "<a href='edit_user.php?id=" . htmlspecialchars($row['id']) . "' class='action-btn edit'>Edit✏️ </a>";
                    echo "<a href='#' class='action-btn delete' onclick=\"confirmDelete(" . htmlspecialchars($row['id']) . ")\">Delete🗑️ </a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="logout.php" class="logout-btn">Logout🚪 </a>

    <script>
        function confirmDelete(userId) {
            if (confirm('Do you really want to delete this user?')) {
                window.location.href = 'delete_user.php?id=' + userId;
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
