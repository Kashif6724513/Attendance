<?php
include('connection.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set("America/New_York");
$userName = $_SESSION['username'];
$userId = $_SESSION['user_id'];

$date = date("Y/m/d");
$time = date("h:i:s A");
$sql  = "SELECT * FROM attendences WHERE user_id = $userId AND attend_date = '$date'";
$result = mysqli_query($conn, $sql);
$attendance = mysqli_fetch_assoc($result);

// Check-In Logic
if (isset($_POST['check_in'])) {
    if (!$attendance) {
        $check_in_time = date('h:i:s A');
        $query = "INSERT INTO attendences (user_id, attend_date, checkin) VALUES ($userId, '$date', '$check_in_time')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Checked in successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You have already checked in today!'); window.location.href='index.php';</script>";
    }
}

// Start Break Logic
if (isset($_POST['start_break'])) {
    if ($attendance && !$attendance['break_in']) {
        $break_in_time = date('h:i:s A');
        $updateQuery = "UPDATE attendences SET break_in = '$break_in_time' WHERE id = " . $attendance['id'];
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Break started successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You have already started a break!'); window.location.href='index.php';</script>";
    }
}

// End Break Logic
if (isset($_POST['end_break'])) {
    if ($attendance && $attendance['break_in'] && !$attendance['break_out']) {
        $break_out_time = date('h:i:s A');
        $updateQuery = "UPDATE attendences SET break_out = '$break_out_time' WHERE id = " . $attendance['id'];
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Break ended successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You have not started a break or already ended it!'); window.location.href='index.php';</script>";
    }
}

// Check-Out Logic
if (isset($_POST['check_out'])) {
    if ($attendance && !$attendance['checkout']) {
        $check_out_time = date('h:i:s A');
        $updateQuery = "UPDATE attendences SET checkout = '$check_out_time' WHERE id = " . $attendance['id'];
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Checked out successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You have already checked out!'); window.location.href='index.php';</script>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            overflow: hidden;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030; /* Keeps the navbar above other content */
        }

        .sidebar {
            position: fixed;
            top: 56px; /* Adjust according to the navbar height */
            left: 0;
            height: calc(100vh - 56px);
            overflow-y: auto;
            width: 20%;
        }

        .content {
            margin-top: 56px; /* Adjust according to the navbar height */
            margin-left: 20%; /* Adjust according to the sidebar width */
            height: calc(100vh - 56px);
            overflow-y: auto;
            padding: 20px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Attendance</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <form class="d-flex">
            <!-- Show Logout button only if user is logged in -->
            <?php if (isset($_SESSION['user_id'])): ?>
              <button class="btn btn-primary mx-2 " type="button"><a class="text-decoration-none text-light" href="logout.php">Logout</a></button>
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
            <span class="fs-4 d-none d-sm-inline">Attendance Bar</span>
          </a>
          <ul class="nav nav-pills flex-column mt-4">
            <li class="nav-item">
              <a href="index.php" class="nav-link text-white">
                <i class="fs-5  bi bi-speedometer"></i><span class="fs-4 ms-2 d-none d-sm-inline">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="profile.php" class="nav-link text-white">
                <i class="fs-8 bi bi-person"></i><span class="fs-7 ms-2 d-none d-sm-inline">Profile</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="attend.php" class="nav-link text-white">
                <i class="fs-8 bi bi-file-earmark-minus"></i><span class="fs-7 ms-2 d-none d-sm-inline">Attendance Form</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    <div class="container col-md-4 my-3">
        <div class="card p-4 shadow-lg" style="margin-top: 80px;">
            <h1 style="margin-bottom: 15px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <form action="" name="form" method="post">
                <?php
                date_default_timezone_set("Asia/Karachi");
                $date = date("Y/m/d");
                $time = date("h:i:s A");
                ?>
                <div class="mb-3">
                    <label class="form-label">Attendance Date</label>
                    <input type="text" class="form-control" name="date" value="<?php echo $date; ?>" required readonly>
                </div>
                <?php if (!$attendance): ?>
                    <div class="mb-3">
                        <label class="form-label">Check-in Time</label>
                        <input type="text" class="form-control" name="checkin" value="<?php echo $time; ?>" required readonly>
                    </div>
                <?php elseif ($attendance && !$attendance['checkout']): ?>
                    <div class="mb-3">
                        <label class="form-label">Check-out Time</label>
                        <input type="text" class="form-control" name="checkout" value="<?php echo $time; ?>" required readonly>
                    </div>
                <?php endif; ?>
                
                <?php if (!$attendance): ?>
                    <button type="submit" name="check_in" class="btn btn-success">Check In</button>
                <?php elseif ($attendance && !$attendance['break_in'] && !$attendance['checkout']): ?>
                    <button type="submit" name="start_break" class="btn btn-warning">Start Break</button>
                    <button type="submit" name="check_out" class="btn btn-danger">Check Out</button>
                <?php elseif ($attendance && $attendance['break_in'] && !$attendance['break_out']): ?>
                    <button type="submit" name="end_break" class="btn btn-info">End Break</button>
                <?php elseif ($attendance && !$attendance['checkout']): ?>
                    <button type="submit" name="check_out" class="btn btn-danger">Check Out</button>
                <?php else: ?>
                    <p>You have already checked in and checked out today.</p>
                <?php endif; ?>
                <button class="btn btn-primary"><a href="index.php" class="text-decoration-none text-white">Back</a></button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
