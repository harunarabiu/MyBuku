<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	require_once "./functions/database_functions.php";
	// print out header here
	$title = "Purchase Process";
	require "./template/header.php";
	

	session_start();

	$_SESSION['err'] = 1;

	// Redirect to login if not logged in
	if (!isset($_SESSION['user'])) {
		$redirect_url = "checkout";
		header("Location: login.php?redirect=$redirect_url");
		exit();
	}

	if (!isset($_SESSION['cart'])){
		header("Location: index.php");
		exit();
	}

	foreach($_POST as $key => $value){
		if(trim($value) == ''){
			$_SESSION['err'] = 0;
		}
		break;
	}

	if($_SESSION['err'] == 0){
		header("Location: checkout.php");
	} else {
		unset($_SESSION['err']);
	}


	// connect database
	$conn = db_connect();

	// validate post section
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$zip_code = $_POST['zip_code'];
	$country = $_POST['country'];



	// find customer
	$userId = $_SESSION['user']['userId'] ?? '';

	$date = date("Y-m-d H:i:s");


	$order_ref = 'ORD-' . uniqid();


	$order_id = insertIntoOrder($userId, $order_ref, $_SESSION['total_price'], $date, $name, $phone, $email, $address, $city, $zip_code, $country);
  	
	if($order_id > 0){

		foreach($_SESSION['cart'] as $isbn => $qty){

			$bookprice = getbookprice($isbn);
			$query = "INSERT INTO order_items (orderId, book_isbn, item_price, quantity) 
         	 VALUES ('$order_id', '$isbn', '$bookprice', '$qty')";


			$result = mysqli_query($conn, $query);

			if(!$result){
				echo "Insert value false!" . mysqli_error($conn2);
				exit;
			}

		}

		if (isset($_SESSION['cart'])) {

			unset($_SESSION['cart']);
			unset($_SESSION['total_items']);
			unset($_SESSION['total_price']);

		}

	} else {

			header("Location: checkout.php");
			exit();

	}

	
?>
	<div class="alert alert-success rounded-0 my-4">Your order <?= $order_ref ?> has been processed sucessfully. We'll be reaching you out to confirm your order. Thanks!</div>

<?php
	if(isset($conn)){
		mysqli_close($conn);
	}
	require_once "./template/footer.php";
?>