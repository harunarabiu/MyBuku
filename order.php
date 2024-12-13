<?php
session_start();
require_once "./functions/database_functions.php";

// Redirect to login if not logged in
if (!isset($_SESSION['user']) ||  !isset($_GET['orderId'])) {
    $redirect_url = "";
    header("Location: orders.php?redirect=$redirect_url");
    exit();
}

// Get order_id from GET request
$order_ref = isset($_GET['orderId']) ? $_GET['orderId'] : null;

if ($order_ref) {
    $conn = db_connect();

    // Get order details from the orders table based on orderid
    $order_details_query = "SELECT * FROM orders WHERE order_ref = '$order_ref'";
    $order_details_result = mysqli_query($conn, $order_details_query);
    $order_details = mysqli_fetch_assoc($order_details_result);

    $order_id=$order_details["orderId"];
    


    // Get order items for this order
    $order_items_query = "SELECT oi.*, b.book_title, b.book_author, b.book_price FROM order_items oi
                          JOIN books b ON oi.book_isbn = b.book_isbn
                          WHERE oi.orderId = '$order_id'";
    $order_items_result = mysqli_query($conn, $order_items_query);
    var_dump($order_items_result);

} else {
    echo "<div class='alert alert-warning'>Order ID not found.</div>";
    exit;
}

$title = "Order Details";
require "./template/header.php";
?>
<h4 class="fw-bolder text-center">Order Details</h4>
<center>
  <hr class="bg-warning" style="width:5em; height:3px; opacity:1">
</center>

<?php if ($order_items_result && mysqli_num_rows($order_items_result) > 0) { ?>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <!-- Order Details Section -->
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="card rounded-0 shadow mb-3">
          <div class="card-body">
            <div class="container-fluid">
    
              <h5>Order Items</h5>
              <table class="table">
                <tr>
                  <th>Item</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($order_items_result)) { ?>
                  <tr>
                    <td><?php echo $row['book_title'] . " by " . $row['book_author']; ?></td>
                    <td><?php echo "$" . $row['book_price']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo "$" . $row['quantity'] * $row['book_price']; ?></td>
                  </tr>
                <?php } ?>
                <tr>
                  <th>Sub Total</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th><?php echo "$" . $order_details['amount']; ?></th>
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
                  <th><?php echo "$" . ($order_details['amount'] + 20); ?></th>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Checkout Form Section (if applicable) -->
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="card rounded-0 shadow">
          <div class="card-header">
            <h5 class="fw-bold">Shipping Information</h5>
          </div>
          <div class="card-body">
            <form method="post" action="process_order.php" class="form-horizontal">
              <div class="mb-2">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_name']); ?>" disabled>
              </div>
              <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_email']); ?>" disabled>
              </div>
              <div class="mb-2">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_phone']); ?>" disabled>
              </div>
              <div class="mb-2">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_address']); ?>" disabled>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="zip_code" class="form-label">Zip Code</label>
                  <input type="text" name="zip_code" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_zip_code']); ?>" disabled>
                </div>
                <div class="col-md-4">
                  <label for="city" class="form-label">City</label>
                  <input type="text" name="city" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_city']); ?>" disabled>
                </div>
                <div class="col-md-4">
                  <label for="country" class="form-label">Country</label>
                  <input type="text" name="country" class="form-control rounded-0" value="<?php echo htmlspecialchars($order_details['ship_country']); ?>" disabled>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } else {
  echo "<div class='alert alert-warning'>No order items found for this order.</div>";
} ?>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
