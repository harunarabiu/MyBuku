<?php
session_start();
if (isset($_SESSION['user'])) {
    header('location:index.php'); // Redirect to homepage if already logged in
}

$title = "Register";

require_once "./template/header.php";
?>
<div class="row justify-content-center my-5">
	<div class="col-lg-4 col-md-6 col-sm-10 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-header">
				<div class="card-title text-center h4 fw-bolder">Register</div>
			</div>
			<div class="card-body">
				<div class="container-fluid">
					<?php if (isset($_SESSION['err_register'])): ?>
						<div class="alert alert-danger rounded-0">
							<?= $_SESSION['err_register'] ?>
						</div>
					<?php 
						unset($_SESSION['err_register']);
						endif;
					?>
					<form class="form-horizontal" method="post" action="register_process.php">
						<div class="mb-3">
							<label for="name" class="control-label ">Full Name</label>
							<input type="text" name="name" class="form-control rounded-0" required>
						</div>
						<div class="mb-3">
							<label for="email" class="control-label ">Email</label>
							<input type="email" name="email" class="form-control rounded-0" required>
						</div>
                        <div class="mb-3">
							<label for="phone" class="control-label ">Phone</label>
							<input type="phone" name="phone" class="form-control rounded-0" required>
						</div>
						<div class="mb-3">
							<label for="password" class="control-label ">Password</label>
							<input type="password" name="password" class="form-control rounded-0" required>
						</div>
						<div class="mb-3">
							<label for="confirm_password" class="control-label ">Confirm Password</label>
							<input type="password" name="confirm_password" class="form-control rounded-0" required>
						</div>
						<div class="mb-3 d-grid">
							<input type="submit" name="submit" class="btn btn-primary rounded-0" value="Register">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require_once "./template/footer.php";
?>
