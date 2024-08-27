<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Modern, responsive styling with a slightly darker grey theme */
        body {
            font-family: 'Arial', sans-serif;
            background: #2c2c2c; /* Slightly darker grey background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 500px;
            background-color: #3a3a3a; /* Darker grey for the container */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* More prominent shadow */
            position: relative;
            box-sizing: border-box;
            margin: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f5f5f5; /* Light text color for better contrast */
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #e0e0e0; /* Light grey for labels */
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #555;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #4d4d4d; /* Darker input background */
            color: #fff; /* Light text color for input */
        }
        .form-group input[type="submit"] {
            background-color: #444;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 12px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .form-group input[type="submit"]:hover {
            background-color: #333;
            transform: scale(1.05);
        }
        .error, .success {
            color: #ff4d4d; /* Red color for error messages */
            margin-bottom: 10px;
            text-align: center;
        }
        .success {
            color: #32cd32; /* Green color for success messages */
        }
        .register-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .register-btn:hover {
            background-color: #333;
        }
        .emoji {
            margin-right: 8px;
        }
        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin: 10px;
                padding: 20px;
            }
            .register-btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="register-btn">📝 Register</a>
        <h2>Login 🔐</h2>
        
        <?php
        include('db.php'); // Include the database connection

        $error = "";
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if user exists
            $sql = "SELECT * FROM users WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    session_start();
                    $_SESSION['email'] = $email;
                    header("Location: welcome.php"); // Redirect to the welcome page
                    exit();
                } else {
                    $error = "⚠️ Invalid password.";
                }
            } else {
                $error = "⚠️ No user found with that email.";
            }
        }

        // Display messages
        if (isset($_GET['status']) && $_GET['status'] == 'loggedout') {
            $success = "You have been logged out successfully. 👋";
        }

        $conn->close();
        ?>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email"><span class="emoji"></span>Email📧</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password"><span class="emoji"></span>Password🔒</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</body>
</html>
