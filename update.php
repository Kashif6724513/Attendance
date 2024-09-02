<?php
include('connection.php');

// Initialize error messages
$usernameError = $emailError = $passwordError = $generalError = "";

// Initialize form values
$fname = $lname = $desgination = $address = $phone = $gender = $username = $email = "";
$id = "";

// Check if the update ID is set
if (isset($_GET['updateid'])) {
    $id = $_GET['updateid'];
    $sql1 = "SELECT * FROM users WHERE id = $id";
    $res = mysqli_query($conn, $sql1);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $fname = $row['fname'];
        $lname = $row['lname'];
        $desgination = $row['desgination'];
        $address = $row['address'];
        $phone = $row['phone'];
        $gender = $row['gender'];
        $role = $row['role'];
        $username = $row['username'];
        $email = $row['email'];
    } else {
        $generalError = "User not found!";
    }
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $desgination = mysqli_real_escape_string($conn, $_POST['desgination']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the username already exists
    $usernameQuery = "SELECT * FROM users WHERE username='$username' AND id != '$id'";
    $usernameResult = mysqli_query($conn, $usernameQuery);
    $count_user = mysqli_num_rows($usernameResult);

    // Check if the email already exists
    $emailQuery = "SELECT * FROM users WHERE email='$email' AND id != '$id'";
    $emailResult = mysqli_query($conn, $emailQuery);
    $count_email = mysqli_num_rows($emailResult);

    if ($count_user > 0) {
        $usernameError = "Username already exists!";
    }

    if ($count_email > 0) {
        $emailError = "Email already exists!";
    }

    if (empty($usernameError) && empty($emailError)) {
        // Update data in the database
        $updateQuery = "UPDATE users SET fname = '$fname', lname = '$lname', desgination = '$desgination', address = '$address', phone = '$phone', gender = '$gender', role = '$role', username = '$username', email = '$email' WHERE id = '$id'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            echo "<script> alert('Data updated successfully!');
            window.location.href = 'employees.php'; </script>";
        } else {
            $generalError = "Failed to update data!";
        }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Attendance</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <button class="btn btn-primary mx-2" type="button"><a class="text-decoration-none text-light" href="logout.php">Logout</a></button>
                    <?php endif; ?>
                </form>
            </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="bg-dark col-auto col-md-2 min-vh-100" style="width: 20%;">
            <div class="bg-dark p-2">
                <a class="d-flex text-decoration-none mt-1 align-item-center text-white">
                    <span class="fs-4 d-none d-sm-inline">Admin Dashboard</span>
                </a>
                <ul class="nav nav-pills flex-column mt-4">
                    <li class="nav-item">
                        <a href="admin_dashboard.php" class="nav-link text-white">
                            <i class="fs-5 bi bi-speedometer"></i><span class="fs-4 ms-2 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="employees.php" class="nav-link text-white">
                            <i class="fs-8 bi bi-person"></i><span class="fs-7 ms-2 d-none d-sm-inline">Employees</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="add_employee.php" class="nav-link text-white">
                            <i class="fs-8 bi bi-file-earmark-minus"></i><span class="fs-7 ms-2 d-none d-sm-inline">Add Employee</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="attendence.php" class="nav-link text-white">
                            <i class="fs-8 bi bi-file-earmark-minus"></i><span class="fs-7 ms-2 d-none d-sm-inline">Attendence</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container col-md-4 my-3">
            <div class="card p-4 shadow-lg" style="margin-top: 60px;">
                <h1 class="text-success my-3 text-center">Update Employee</h1>
                <form action="update.php?updateid=<?= $id ?>" name="form" method="post" autocomplete="off">
                    <?php if (!empty($generalError)) { echo "<div class='alert alert-danger'>$generalError</div>"; } ?>
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($fname) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($lname) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <select class="form-select" name="desgination">
                            <option value="Frontend Developer" <?= $desgination == 'Frontend Developer' ? 'selected' : '' ?>>Frontend Developer</option>
                            <option value="Backend Developer" <?= $desgination == 'Backend Developer' ? 'selected' : '' ?>>Backend Developer</option>
                            <option value="Fullstack Developer" <?= $desgination == 'Fullstack Developer' ? 'selected' : '' ?>>Fullstack Developer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Address</label>
                        <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                            <option value="Admin" <?= $role == 'Admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="Employee" <?= $role == 'Employee' ? 'selected' : '' ?>>Employee</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Username</label>
                        <input type="text" class="form-control" name="user" value="<?= htmlspecialchars($username) ?>" required>
                        <?php if (!empty($usernameError)) { echo "<div class='text-danger'>$usernameError</div>"; } ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        <?php if (!empty($emailError)) { echo "<div class='text-danger'>$emailError</div>"; } ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Update Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
