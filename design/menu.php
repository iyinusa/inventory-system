<?php
	$drole = $_SESSION['drole'];
	if ($drole == "Admin")
	{
		$imenu .= '
			<li>
				<a class="idash" href="dashboard.php">Dashboard</a>
			</li>
			<li>
				<a class="iorder" href="order_request.php">Order Requests</a>
			</li>
			<li>
				<a class="iprod" href="manage_products.php">Manage Products</a>
			</li>
			<li>
				<a class="iprof" href="profile.php">Profile</a>
			</li>
			<li>
				<a class="icart" href="cart.php">Cart</a>
			</li>
		';
	} else
	{
		$imenu .= '
			<li>
				<a class="iprof" href="profile.php">Profile</a>
			</li>
			<li>
				<a class="icart" href="cart.php">Cart</a>
			</li>
		';
	}
?>