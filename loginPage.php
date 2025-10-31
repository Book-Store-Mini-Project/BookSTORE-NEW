<?php
session_start();
require_once 'config/db_connection.php';

$signup_msg = "";
$signin_msg = "";

// ----------------- SIGN UP -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_plain = $_POST['password'];

    if ($name === "" || $email === "" || $password_plain === "") {
        $signup_msg = "Please fill all fields.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $signup_msg = "Email already registered.";
        } else {
            $hash = password_hash($password_plain, PASSWORD_BCRYPT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert, "sss", $name, $email, $hash);
            if (mysqli_stmt_execute($insert)) {
                $signup_msg = "Registration successful! You can sign in now.";
            } else {
                $signup_msg = "Registration failed. Try again.";
            }
            mysqli_stmt_close($insert);
        }
        mysqli_stmt_close($check);
    }
}

// ----------------- SIGN IN -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = trim($_POST['L_email']);
    $password_plain = $_POST['L_password'];

    if ($email === "" || $password_plain === "") {
        $signin_msg = "Please enter email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $uid, $uname, $uemail, $uhash);

        if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password_plain, $uhash)) {
                $_SESSION['user_id'] = $uid;
                $_SESSION['user_name'] = $uname;
                $_SESSION['user_email'] = $uemail;
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: index.php");
                exit;
            } else {
                $signin_msg = "Incorrect password.";
            }
        } else {
            $signin_msg = "No account found with that email.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>READIFY Bookstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>

    <div class="container" id="container">
        <!-- Sign Up Form -->
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>READIFY</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signup">Sign Up</button>
                <?php if ($signup_msg != "") echo "<p class='msg'>$signup_msg</p>"; ?>
            </form>
        </div>

        <!-- Sign In Form -->
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="email" name="L_email" placeholder="Email" required>
                <input type="password" name="L_password" placeholder="Password" required>
                <a href="#">Forget Your Password?</a>
                <button type="submit" name="signin">Sign In</button>
                <?php if ($signin_msg != "") echo "<p class='msg'>$signin_msg</p>"; ?>
            </form>
        </div>

        <!-- Toggle Section -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/login.js"></script>
</body>

</html>
