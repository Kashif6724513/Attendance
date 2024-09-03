<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Attendance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      margin: 0;
      overflow: hidden; /* Prevent body from scrolling */
    }
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1030;
    }
    .sidebar {
      position: fixed;
      top: 56px; /* Adjust based on navbar height */
      left: 0;
      height: calc(100vh - 56px); /* Full height minus navbar */
      width: 20%;
      background-color: #343a40;
      overflow-y: auto;
      padding-top: 20px;
    }
    .content {
      margin-left: 20%; /* Adjust based on sidebar width */
      margin-top: 56px; /* Adjust based on navbar height */
      height: calc(100vh - 56px); /* Full height minus navbar */
      overflow-y: auto; /* Enable vertical scrolling for content */
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
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <form class="d-flex">
          <!-- Show Logout button only if user is logged in -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <button class="btn btn-primary mx-2" type="button"><a class="text-decoration-none text-light" href="logout.php">Logout</a></button>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </nav>

  <div class="sidebar">
    <a class="d-flex text-decoration-none mt-1 align-items-center text-white">
      <span class="fs-4 d-none d-sm-inline">Attendance Bar</span>
    </a>
    <ul class="nav nav-pills flex-column mt-4">
      <li class="nav-item">
        <a href="index.php" class="nav-link text-white">
          <i class="fs-5 bi bi-speedometer"></i><span class="fs-4 ms-2 d-none d-sm-inline">Dashboard</span>
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

  <div class="content">
    <h1 class="text-success">Profile</h1>
    <div class="container">
      <div class="card p-4 shadow-lg">
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th scope="col">Id</th>
              <th scope="col">First Name</th>
              <th scope="col">Last Name</th>
              <th scope="col">Designation</th>
              <th scope="col">Address</th>
              <th scope="col">Phone</th>
              <th scope="col">Gender</th>
              <th scope="col">Username</th>
              <th scope="col">Email</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include('connection.php');
            session_start();
            if (!isset($_SESSION['user_id'])) {
              header("Location: login.php");
              exit;
            }
            $userId = $_SESSION['user_id'];  // Get the logged-in user's ID

            // Fetch only the user details of the logged-in user
            $sql = "SELECT * FROM users WHERE id = $userId";
            $result = mysqli_query($conn, $sql);

            if ($result) {
              while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $fname = $row['fname'];
                $lname = $row['lname'];
                $desgination = $row['desgination'];
                $address = $row['address'];
                $phone = $row['phone'];
                $gender = $row['gender'];
                $username = $row['username'];
                $email = $row['email'];

                echo '<tr>
                  <th scope="row">' . $id . '</th>
                  <td>' . $fname . '</td>
                  <td>' . $lname . '</td>
                  <td>' . $desgination . '</td>
                  <td>' . $address . '</td>
                  <td>' . $phone . '</td>
                  <td>' . $gender . '</td>
                  <td>' . $username . '</td>
                  <td>' . $email . '</td>
                </tr>';
              }
            } else {
              echo "<tr><td colspan='9'>No records found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
