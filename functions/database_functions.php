<?php
	function db_connect(){
		// $db_host = "mynetwork-rdsinstance-ld8ekrenleuz.chqmq2008d5p.ap-southeast-1.rds.amazonaws.com";
		// $db_name = "mybuku";
		// $db_user = "admin";
        // $db_password = "MyBuku123!";


		//local DB configurations

		$db_host = "localhost";
		$db_name = "mybuku";
		$db_user = "root";
        $db_password = "";
        
		
		$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

		if(!$conn){
			echo "Can't connect database " . mysqli_connect_error($conn);
			exit;
		}
		return $conn;
	}

	function select4LatestBook($conn){
		$row = array();
		$query = "SELECT book_isbn, book_image, book_title FROM books ORDER BY abs(unix_timestamp(created_at)) DESC";
		$result = mysqli_query($conn, $query);
		if(!$result){
		    echo "Can't retrieve data " . mysqli_error($conn);
		    exit;
		}
		for($i = 0; $i < 4; $i++){
			array_push($row, mysqli_fetch_assoc($result));
		}
		return $row;
	}

   // Fetch all orders (for admins)
	function getAllOrders($conn) {
		$conn = db_connect();
		$query = "SELECT * FROM orders";
		return mysqli_query($conn, $query);
	}

	// Fetch orders for a specific user (for regular users)
	function getUserOrders($conn, $userId) {
		$conn = db_connect();
		$query = "SELECT * FROM orders WHERE userId = '$userId'";
		return mysqli_query($conn, $query);
	}

	// Fetch user name based on user_id
	function getUserName($conn, $userId) {
		$conn = db_connect();
		$query = "SELECT name FROM users WHERE userId = '$userId'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		return $row['name'];
	}


	function getBookByIsbn($conn, $isbn){
		$query = "SELECT book_title, book_author, book_price FROM books WHERE book_isbn = '$isbn'";
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo "Can't retrieve data " . mysqli_error($conn);
			exit;
		}
		return $result;
	}

	function getorderId($conn, $userId){
		$query = "SELECT orderId FROM orders WHERE userId = '$userId'";
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo "retrieve data failed!" . mysqli_error($conn);
			exit;
		}
		$row = mysqli_fetch_assoc($result);
		return $row['orderId'];
	}

	function insertIntoOrder($userId, $order_ref, $total_price, $date, $ship_name, $ship_phone, $ship_email, $ship_address, $ship_city, $ship_zip_code, $ship_country){
		$conn = db_connect();
		$query = "INSERT INTO orders 
          (order_ref, userId, amount, order_date, ship_name, ship_phone, ship_email, ship_address, ship_city, ship_zip_code, ship_country) 
          VALUES 
          ('" . $order_ref . "', '" . $userId . "', '" . $total_price . "', '" . $date . "', '" . $ship_name . "', '" . $ship_phone . "', '" . $ship_email . "', '" . $ship_address . "', '" . $ship_city . "', '" . $ship_zip_code . "', '" . $ship_country . "')";

		
		$result = mysqli_query($conn, $query);

		if(!$result){
			echo "Insert orders failed " . mysqli_error($conn);
			exit;
		}

		// Get the inserted order's auto-incremented ID
		$order_id = mysqli_insert_id($conn);

		// Return the order ID
		return $order_id;
	}

	function getbookprice($isbn){
		$conn = db_connect();
		$query = "SELECT book_price FROM books WHERE book_isbn = '$isbn'";
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo "get book price failed! " . mysqli_error($conn);
			exit;
		}
		$row = mysqli_fetch_assoc($result);
		return $row['book_price'];
	}

	function getuserId($name, $address, $city, $zip_code, $country){
		$conn = db_connect();
		$query = "SELECT userId from customers WHERE 
		`name` = '$name' AND 
		`address`= '$address' AND 
		city = '$city' AND 
		zip_code = '$zip_code' AND 
		country = '$country'";
		$result = mysqli_query($conn, $query);
		// if there is customer in db, take it out
		if($result->num_rows > 0){
			$row = mysqli_fetch_assoc($result);
			return $row['userId'];
		} else {
			return null;
		}
	}

	function setuserId($name, $address, $city, $zip_code, $country){
		$conn = db_connect();
		$query = "INSERT INTO customers VALUES 
			('', '" . $name . "', '" . $address . "', '" . $city . "', '" . $zip_code . "', '" . $country . "')";

		$result = mysqli_query($conn, $query);
		if(!$result){
			echo "insert false !" . mysqli_error($conn);
			exit;
		}
		$userId = mysqli_insert_id($conn);
		return $userId;
	}

	function getPubName($conn, $pubid){
		$query = "SELECT publisher_name FROM publisher WHERE publisherId = '$pubid'";
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo "Can't retrieve data " . mysqli_error($conn);
			exit;
		}
		if(mysqli_num_rows($result) == 0){
			echo "Empty books ! Something wrong! check again";
			exit;
		}

		$row = mysqli_fetch_assoc($result);
		return $row['publisher_name'];
	}

	function getAll($conn){
		$query = "SELECT * from books ORDER BY book_isbn DESC";
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo "Can't retrieve data " . mysqli_error($conn);
			exit;
		}
		return $result;
	}
?>