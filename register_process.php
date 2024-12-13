<?php
session_start();
require_once "./functions/database_functions.php";

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve and sanitize form inputs
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation to check if any required fields are empty
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['err_register'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    // Check if the passwords match
    if ($password !== $confirm_password) {
        $_SESSION['err_register'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Sanitize email to avoid XSS and invalid characters
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['err_register'] = "Invalid email format.";
        header("Location: register.php");
        exit();
    }

    // Sanitize name (Remove any tags that could cause XSS)
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');

    // Sanitize and escape the email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Establish a connection to the database
    $conn = db_connect();

    // Check if the email is already registered
    $email_query = "SELECT * FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $email_query)) {
        mysqli_stmt_bind_param($stmt, "s", $email); // Bind the email as a parameter
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $_SESSION['err_register'] = "This email is already registered.";
            header("Location: register.php");
            exit();
        }

        mysqli_stmt_close($stmt); // Close the prepared statement
    } else {
        $_SESSION['err_register'] = "Database error. Please try again.";
        header("Location: register.php");
        exit();
    }

    // Insert new user into the database
    $insert_query = "INSERT INTO users (name, email, phone, passwordHash) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $insert_query)) {
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email,$phone, $hashed_password); // Bind the parameters
        $insert_result = mysqli_stmt_execute($stmt);

        if ($insert_result) {
            $userId = mysqli_insert_id($conn);
            $_SESSION['user'] = ['name' => $name, 'email' => $email, 'phone' => $phone, 'userId' => $userId];
            header("Location: index.php"); // Redirect to home page after successful registration
        } else {
            $_SESSION['err_register'] = "Registration failed. Please try again.";
            header("Location: register.php");
        }

        mysqli_stmt_close($stmt); // Close the prepared statement

    } else {

        $_SESSION['err_register'] = "Database error. Please try again.";
        header("Location: register.php");
        exit();

    }

    // Close the database connection
    mysqli_close($conn);
}
?>
