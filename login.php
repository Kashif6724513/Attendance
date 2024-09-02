<?php
session_start(); 
include('connection.php'); 

// Check if the user is already logged in, if yes then redirect based on role
if (isset($_SESSION['user_id'])) {
    // Redirect to appropriate dashboard based on role
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

// Handle login form submission
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    // Fetch user information based on username or email
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // Check if user exists and password matches
    if ($row) {
        if (password_verify($pass, $row['password'])) {
            // Store user information in the session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; 

            // Redirect to the appropriate page based on user role
            if ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            // Invalid password
            echo "<script>
                    alert('Invalid username or password!');
                    window.location.href = 'login.php';
                  </script>";
        }
    } else {
        // Invalid username
        echo "<script>
                alert('Invalid username or password!');
                window.location.href = 'login.php';
              </script>";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="">Attendance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <!-- If user is not logged in, show SignUp and Login buttons -->
                        
                        <button class="btn btn-primary mx-2" type="button"><a class="text-decoration-none text-light" href="login.php">Login</a></button>
                        
                    <?php else: ?>
                        <!-- If user is logged in, show Logout button -->
                        <button class="btn btn-danger mx-2" type="button"><a class="text-decoration-none text-light" href="logout.php">Logout</a></button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container col-md-4 my-3">
        <div class="card p-4 shadow-lg" style="margin-top: 80px;">
            <h1 class="text-success my-3 text-center">Login Form</h1>
            <form action="login.php" name="form" method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Enter Username/Email</label>
                    <input type="text" class="form-control" name="user" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="pass" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
