<?php
session_start();
require_once "./functions/database_functions.php";

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    $redirect_url = "checkout";
    header("Location: login.php?redirect=$redirect_url");
    exit();
}

// Default form data from session
$form_data = [
    'name' => $_SESSION['user']['name'] ?? '',
    'phone' => $_SESSION['user']['phone'] ?? '',
    'email' => $_SESSION['user']['email'] ?? '',
    'address' => $_SESSION['user']['address'] ?? '',
    'city' => $_SESSION['user']['city'] ?? '',
    'zip_code' => $_SESSION['user']['zip_code'] ?? '',
    'country' => $_SESSION['user']['country'] ?? ''
];

$title = "Checking out";
require "./template/header.php";
?>
<h4 class="fw-bolder text-center">Checkout</h4>
<center>
  <hr class="bg-warning" style="width:5em; height:3px; opacity:1">
</center>
<?php
if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
?>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <!-- Order Details Section -->
      <div class="col-lg-6 col-md-12 mb-3">
       <div class="card rounded-0 shadow mb-3">
		<div class="card-body">
			<div class="container-fluid">
				<table class="table">
					<tr>
						<th>Item</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Total</th>
					</tr>
						<?php
							foreach($_SESSION['cart'] as $isbn => $qty){
								$conn = db_connect();
								$book = mysqli_fetch_assoc(getBookByIsbn($conn, $isbn));
						?>
					<tr>
						<td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
						<td><?php echo "$" . $book['book_price']; ?></td>
						<td><?php echo $qty; ?></td>
						<td><?php echo "$" . $qty * $book['book_price']; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th>Sub Total</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th><?php echo "$" . $_SESSION['total_price']; ?></th>
					</tr>
					<tr>
						<td>Shipping</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>20.00</td>
					</tr>
					<tr>
						<th>GRAND TOTAL</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th><?php echo "$" . ($_SESSION['total_price'] + 20); ?></th>
					</tr>
				</table>
			</div>
		</div>
	</div>
      </div>
      <!-- Checkout Form Section -->
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="card rounded-0 shadow">
          <div class="card-header">
            <h5 class="fw-bold">Shipping Information</h5>
          </div>
          <div class="card-body">
            <form method="post" action="process_order.php" class="form-horizontal">
              <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 1) { ?>
                <p class="text-danger">All fields have to be filled</p>
              <?php } ?>
              <div class="mb-2">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['name']); ?>" required>
              </div>
              <div class="mb-2">
                
              </div>
			  <div class="row mb-3">
                <div class="col-md-6">
                 	<label for="email" class="form-label">Email</label>
                	<input type="text" name="email" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['email']); ?>" required>
                </div>
            
                <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['phone']); ?>" required>
                </div>
              </div>
              <div class="mb-2">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['address']); ?>" required>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="zip_code" class="form-label">Zip Code</label>
                  <input type="text" name="zip_code" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['zip_code']); ?>" required>
                </div>
                <div class="col-md-4">
                  <label for="city" class="form-label">City</label>
                  <input type="text" name="city" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['city']); ?>" required>
                </div>
                <div class="col-md-4">
                  <label for="country" class="form-label">Country</label>
                  <input type="text" name="country" class="form-control rounded-0" value="<?php echo htmlspecialchars($form_data['country']); ?>" required>
                </div>
              </div>
              <div class="d-grid">
                <input type="submit" name="submit" value="Proceed" class="btn btn-primary rounded-0">
              </div>
            </form>
           
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
} else {
    echo "<div class=\"alert alert-warning rounded-0\">Your cart is empty! Please make sure you add some books in it!</div>";
}
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
