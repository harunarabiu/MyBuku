<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap/css/styles.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="./bootstrap/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  </head>

  <body>
    <div class="clear-fix pt-5 pb-3"></div>
   <nav class="navbar navbar-expand-lg navbar-expand-md navbar-light bg-warning bg-gradient fixed-top">
      <div class="container">
        <!-- Brand -->
        <a class="navbars-brand" href="index.php">MyBuku Store</a>

        <!-- Toggler button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="topNav">
          <!-- Right-aligned menu -->
          <ul class="navbar-nav ms-auto">
            <!-- Conditional Admin Links -->
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>
              <li class="nav-item"><a class="nav-link" href="admin_book.php"><span class="fa fa-th-list"></span> Book Manager</a></li>
              <li class="nav-item"><a class="nav-link" href="admin_add.php"><span class="far fa-plus-square"></span> Add New Book</a></li>
              <?php endif; ?>
              <li class="nav-item"><a class="nav-link" href="publisher_list.php"><span class="fa fa-paperclip"></span> Publisher</a></li>
              <li class="nav-item"><a class="nav-link" href="books.php"><span class="fa fa-book"></span> Books</a></li>
              <li class="nav-item">
                <a class="nav-link position-relative" href="cart.php">
                  <i class="fa fa-shopping-cart" style="font-size: 20px;"></i>
                  <?php if (isset($_SESSION['total_items']) && $_SESSION['total_items'] > 0): ?>
                    <span class="badge bg-danger position-absolute" style="top: -5px; left: 15px; font-size: 10px; padding: 2px 5px;">
                      <?php echo $_SESSION['total_items']; ?>
                    </span>
                  <?php endif; ?>
                  Cart
                </a>
              </li>

         

            <!-- Orders Link -->
            <li class="nav-item"><a class="nav-link" href="orders.php"><span class="fa fa-box"></span> Orders</a></li>

            <!-- User Avatar and Dropdown -->
            <li class="nav-item dropdown">
              <?php if (isset($_SESSION['user'])): ?>
                <!-- User logged in -->
                <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-user-circle" style="font-size: 24px; color: #333;"></i>
                  <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                  <!-- <li><a class="dropdown-item" href="profile.php">Profile</a></li> -->
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
              <?php else: ?>
                <!-- User not logged in -->
                <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-user-circle" style="font-size: 24px; color: #333;"></i> Account
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                  <li><a class="dropdown-item" href="login.php">Login</a></li>
                  <li><a class="dropdown-item" href="register.php">Register</a></li>
                </ul>
              <?php endif; ?>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <?php
      if(isset($title) && $title == "Home") {
    ?>
    <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="container">
        <h1>Welcome to MyBuku Store</h1>
        <hr>
      </div>
    <?php } ?>

    <div class="container" id="main">