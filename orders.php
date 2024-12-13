<?php
session_start();
$title = "List Orders";
require_once "./template/header.php";
require_once "./functions/database_functions.php";
$conn = db_connect();

// Check if user is logged in and is an admin
if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'ADMIN') {
    // Admin sees all orders
    $result = getAllOrders($conn);
} elseif (isset($_SESSION['user']['userId']) || isset($_SESSION['user'])) {
    // Regular user sees only their orders
    $userId = $_SESSION['user']['userId'];
    $result = getUserOrders($conn, $userId);
} else {
    // Redirect to login if user is not logged in
    header("Location: login.php");
    exit;
}

?>
<h4 class="fw-bolder text-center">Orders List</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<?php if(isset($_SESSION['order_success'])): ?>
    <div class="alert alert-success rounded-0">
        <?= $_SESSION['order_success'] ?>
    </div>
<?php 
    unset($_SESSION['order_success']);
endif;
?>

<div class="card rounded-0">
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-striped table-bordered">
                <colgroup>
                    <col width="10%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="15%">
                    <col width="20%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Order Date</th>
                        <th>Total Price</th>
                        <th>Shipping Name</th>
                        <th>Shipping Address</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td class="px-2 py-1 align-middle"><a href="order.php?orderId=<?php echo $row['order_ref']; ?>"><?php echo $row['order_ref']; ?></a></td>
                        <td class="px-2 py-1 align-middle"><?php echo getUserName($conn, $row['userId']); ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['order_date']; ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo "$" . $row['amount']; ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['ship_name']; ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['ship_address']; ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['ship_city']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
if(isset($conn)) { mysqli_close($conn); }
require_once "./template/footer.php";
?>
