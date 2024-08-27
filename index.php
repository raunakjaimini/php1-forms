<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .error {
            color: #ff4d4d; /* Red color for error messages */
            margin-bottom: 10px;
            text-align: center;
        }
        .success {
            color: #32cd32; /* Green color for success messages */
            margin-bottom: 10px;
            text-align: center;
        }
        .login-button {
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
        .login-button:hover {
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
            .login-button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="login.php" class="login-button">🔑 Login</a>
        <h2>Register 📝</h2>
        <?php
        include('db.php'); // Include the database connection

        $error = $success = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $mobile_number = $_POST['mobile_number'];
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            $dob = $_POST['dob'];

            // Basic validation
            if (empty($firstname) || empty($lastname) || empty($email) || empty($mobile_number) || empty($password) || empty($repassword) || empty($dob)) {
                $error = "⚠️ All fields are required.";
            } else if ($password !== $repassword) {
                $error = "⚠️ Passwords do not match.";
            } else {
                // Validate mobile number
                if (!preg_match('/^\d{10}$/', $mobile_number)) {
                    $error = "⚠️ Mobile number must be exactly 10 digits.";
                } else {
                    // Password validation
                    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{4,}$/', $password)) {
                        $error = "⚠️ Password must be at least 4 characters long, contain at least one uppercase letter, one number, and one special character.";
                    } else {
                        // Hash the password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Insert the user into the database
                        $sql = "INSERT INTO users (firstname, lastname, email, mobile_number, password, dob) 
                                VALUES (?, ?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $mobile_number, $hashed_password, $dob);

                        if ($stmt->execute()) {
                            $success = "🎉 Registration successful!";
                            header("Location: login.php"); // Redirect to the login page
                            exit();
                        } else {
                            $error = "Error: " . $stmt->error;
                        }
                    }
                }
            }
        }

        $conn->close();
        ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="firstname"><span class="emoji"></span>First Name👤</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname"><span class="emoji"></span>Last Name👤</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="email"><span class="emoji"></span>Email📧</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mobile_number"><span class="emoji"></span>Mobile Number📱</label>
                <input type="text" id="mobile_number" name="mobile_number" pattern="\d{10}" title="Mobile number must be exactly 10 digits" required>
            </div>
            <div class="form-group">
                <label for="password"><span class="emoji"></span>Password🔒</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="repassword"><span class="emoji"></span>Re-enter Password🔒</label>
                <input type="password" id="repassword" name="repassword" required>
            </div>
            <div class="form-group">
                <label for="dob"><span class="emoji"></span>Date of Birth📅</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var repassword = document.getElementById("repassword").value;
            var passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{4,}$/;

            if (!passwordPattern.test(password)) {
                alert("⚠️ Password must be at least 4 characters long, contain at least one uppercase letter, one number, and one special character.");
                return false;
            }
            if (password !== repassword) {
                alert("⚠️ Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
