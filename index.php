<?php
session_start(); 

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
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
              <a href="" class="nav-link text-white">
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
      <!-- content -->
      <div class="p-3" style="width: 80%;">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p> You are logged in.</p>
        <div class="container  my-4">
          <div class="card p-4 shadow-lg">
            <table class="table table-bordered">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Id</th>
                  <th scope="col">Attend Date</th>
                  <th scope="col">Checkin</th>
                  <th scope="col">Start Break</th>
                  <th scope="col">End Break</th>
                  <th scope="col">Checkout</th>
                  <th scope="col">Total Checkin Time</th>
                  <th scope="col">Total Break Time</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <?php
                include('connection.php');
                $userId = $_SESSION['user_id'];  // Get the logged-in user's ID

                // Fetch only the attendance records of the logged-in user
                $sql = "SELECT * FROM attendences WHERE user_id = $userId";
                $result = mysqli_query($conn, $sql);
                $displayTimeZone = new DateTimeZone("Asia/Karachi");
                if ($result) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $originalTimeZone = new DateTimeZone("America/New_York");
                    $id = $_SESSION['user_id'];
                    $attend_date = $row['attend_date'];
                    $checkin = $row['checkin'];
                    $break_in = $row['break_in'];
                    $break_out = $row['break_out'];
                    $checkout = $row['checkout'];
                
                    // Convert times to desired time zone
                    $checkinDateTime = new DateTime($checkin, $originalTimeZone);
                    $checkinDateTime->setTimezone($displayTimeZone);
                    $checkinFormatted = $checkinDateTime->format('h:i:s A');
                
                    $breakinFormatted = '';
                    if (isset($row['break_in'])) {
                        $breakinDateTime = new DateTime($break_in, $originalTimeZone);
                        $breakinDateTime->setTimezone($displayTimeZone);
                        $breakinFormatted = $breakinDateTime->format('h:i:s A');
                    }
                
                    $breakoutFormatted = '';
                    if (isset($row['break_out'])) {
                        $breakoutDateTime = new DateTime($break_out, $originalTimeZone);
                        $breakoutDateTime->setTimezone($displayTimeZone);
                        $breakoutFormatted = $breakoutDateTime->format('h:i:s A');
                    }
                
                    $checkoutFormatted = '';
                    if (isset($row['checkout'])) {
                        $checkoutDateTime = new DateTime($checkout, $originalTimeZone);
                        $checkoutDateTime->setTimezone($displayTimeZone);
                        $checkoutFormatted = $checkoutDateTime->format('h:i:s A');
                    }
                
                    // Calculate Total Break Time
                    $totalBreakTime = '';
                    if (!empty($breakinFormatted) && !empty($breakoutFormatted)) {
                        $breakinDateTime = new DateTime($break_in);
                        $breakoutDateTime = new DateTime($break_out);
                        $interval = $breakinDateTime->diff($breakoutDateTime);
                        $totalBreakTime = $interval->format('%H:%I:%S');
                    }
                
                    // Calculate Total Login Time
                    $totalLoginTime = '';
                    if (!empty($checkin) && !empty($checkout)) {
                        $checkinDateTime = new DateTime($checkin);
                        $checkoutDateTime = new DateTime($checkout);
                        $totalInterval = $checkinDateTime->diff($checkoutDateTime);
                
                        // Subtract break time from total time
                        if (!empty($totalBreakTime)) {
                            $totalLoginInterval = $totalInterval->format('%H:%I:%S');
                            $totalLoginTime = (new DateTime($totalLoginInterval))
                                ->diff(new DateTime($totalBreakTime))
                                ->format('%H:%I:%S');
                        } else {
                            $totalLoginTime = $totalInterval->format('%H:%I:%S');
                        }
                    }
                
                    echo '<tr>
                        <th scope="row">' . $id . '</th>
                        <td>' . $attend_date . '</td>
                        <td>' . $checkinFormatted . '</td>
                        <td>' . $breakinFormatted . '</td>
                        <td>' . $breakoutFormatted . '</td>
                        <td>' . $checkoutFormatted . '</td>
                        <td>' . $totalLoginTime . '</td>
                        <td>' . $totalBreakTime . '</td>
                    </tr>';
                }
               
                  }
                 else {
                  echo "<tr><td colspan='4'>No records found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>