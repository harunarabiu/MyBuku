<?php
	session_start();
	if(!isset($_POST['submit'])){
		echo "Something wrong! Check again!";
		exit;
	}
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	

	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	
	if($email == "" || $password == ""){
		echo "Email and passwordword are required!";
		exit;
	}

	$email = mysqli_real_escape_string($conn, $email);
	$password = mysqli_real_escape_string($conn, $password);
	$passwordHash = password_hash($password, PASSWORD_DEFAULT);


	// Secure login check

	// Prepare SQL statement to prevent SQL injection
	$query = "SELECT `email`, `passwordHash`, `user_type` FROM `users` WHERE `email` = ?";
	$stmt = mysqli_prepare($conn, $query);

	// Bind parameters and execute
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	// Fetch the result
	$result = mysqli_stmt_get_result($stmt);

	if ($result->num_rows <= 0) {
		$_SESSION['err_login'] = "Incorrect Details";
		header("Location: login.php");
		exit;
	}

	$row = mysqli_fetch_assoc($result);

	// Verify the password
	if (!password_verify($password, $row['passwordHash'])) {
		$_SESSION['err_login'] = "Incorrect Details";
		header("Location: login.php");
		exit;
	}

	$_SESSION['user'] = [
		'userId' => $row['userId'],
		'email'  => $row['email'],
		'name'   => $row['name'],
		'phone'  => $row['phone'],
		'user_type' => $row['user_type'],
	];

	// Check user type
	if ($row['user_type'] === "ADMIN") {
		$_SESSION['admin'] = true;
	}

	// Close connection
	if (isset($conn)) {
		mysqli_close($conn);
	}


	

	header("Location: admin_book.php");
?>