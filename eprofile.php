<?php
	//start session
	session_start();
	$imenu = '';
	
	if (isset($_SESSION['dname']))
	{
		$cust = $_SESSION['dname'];
		$logv = 'Welcome, <b>'.$cust.'</b> | <a href="logout.php">Log Out</a>';
		
		//create menu based on role
		include("design/menu.php");
	} else {
		//redirect to login page
		header("location: index.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="styles/lay.css"/>

<title>Inventory System | Edit Profile</title>
</head>

<body>
	<div id="all">
    	<div id="header">
        	<div id="hd_rz">
            	<?php include('design/header.php'); ?>
            </div>
        </div>
        
        <div id="sitecont">
        	<div class="hm_flash">
            	<?php include('design/hmflash.php'); ?>
            </div>
            <br />
        	<div id="sc_rz">
            	<div class="topic">Customers > Edit Profile</div>
                <div id="dash">
                	<div style="border:1px solid #900; border-bottom:none; padding:10px; background-color:#EEE;"><?php echo $logv; ?></div>
                    <div class="dash_left">
                    	<div id="iprof" class="imenu">
                        	<ul>
                            	<?php echo $imenu; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="dash_right">
                    	<div class="dr_rz">
                            <!-- Pull Customer Profile -->
                            <?php 
                                //connect database
								include("design/connect.php");
								if (isset($_SESSION['dname']))
								{
									if (isset($_POST['btnChange']))
									{
										//check for password change request
										$uid = $_SESSION['cus_id'];
										$pass_msg = '';
										
										//check validation
										$uold = $_POST['nold'];
										$unew = $_POST['nnew'];
										$uconfirm = $_POST['nconfirm'];
										
										if (!$uold || !$unew || !$uconfirm)
										{
											$pass_msg = '<div class="msg">All fields are required</div>';
										} else if ($unew != $uconfirm)
										{
											$pass_msg = '<div class="msg">Password not matched</div>';
										} else
										{
											//check if password is correct
											$hash = md5($uold);
											$pass = mysql_query("SELECT * FROM customer WHERE cus_id='$uid' AND password='$hash'");
											$pass_chk = mysql_num_rows($pass);
											if ($pass_chk <= 0)
											{
												$pass_msg = '<div class="msg">Password not correspond to Customer</div>';	
											} else
											{
												//update password
												$hash2 = md5($unew);
												mysql_query("UPDATE customer SET password='$hash2' WHERE cus_id='$uid'") or die(mysql_error());
												$pass_msg = '<div class="msg">Password changed successfullly</div>';
											}
										}
									} else if (isset($_POST['btnUpdate']))
									{
										//check for profile update change request
										$uid = $_SESSION['cus_id'];
										$ufull = $_POST['nfull'];
										$uemail = $_POST['nemail'];
										$uphone = $_POST['nphone'];
										$uaddress = $_POST['naddress'];
										
										mysql_query("UPDATE customer SET fullname='$ufull',email='$uemail',phone='$uphone',address='$uaddress' WHERE cus_id='$uid'") or die(mysql_error());
										
										//redirect to profile page
										header("location: profile.php");
											
									} else
									{
										$pass_msg = '';
										$uid = $_SESSION['cus_id'];
										$uaddress = '';$uphone = '';$uemail = '';$uaddress = '';
										
										//pull customer information
										$pull = mysql_query("SELECT * FROM customer WHERE cus_id='$uid'");
										while($row=mysql_fetch_assoc($pull))
										{
											$ufull = $row['fullname'];
											$uemail = $row['email'];
											$uphone = $row['phone'];
											$uaddress = $row['address'];
										}
									}
								}
								
                            ?>
                            
                             <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                                <fieldset>
                                    <legend>Change Password</legend>
                                    <?php echo $pass_msg; ?>
                                    <table>
                                        <tr>
                                            <td width="120px"><b>Old Password:</b></td>
                                            <td>
                                            	<input type="password" name="nold"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>New Password:</b></td>
                                            <td>
                                            	<input type="password" name="nnew"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Confirm Password:</b></td>
                                            <td>
                                            	<input type="password" name="nconfirm"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                            	<input type="submit" name="btnChange" value="Change Password" />
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </form>
                            <br />
                            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                                <fieldset>
                                    <legend>Update Profile Information</legend>
                                    <table>
                                        <tr>
                                            <td width="120px"><b>Full Name:</b></td>
                                            <td>
                                            	<input type="text" name="nfull" value="<?php echo $ufull; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Email Address:</b></td>
                                            <td>
                                            	<input type="text" name="nemail" value="<?php echo $uemail; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Phone:</b></td>
                                            <td>
                                            	<input type="text" name="nphone" value="<?php echo $uphone; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Address:</b></td>
                                            <td>
                                            	<textarea name="naddress"><?php echo $uaddress; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                            	<input type="submit" name="btnUpdate" value="Update Profile" />
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!--<div class="topic">Our Brands</div>
                <div id="b_brands">
                	<?php include('design/brands.php'); ?>
                </div>-->
            </div>
        </div>
    </div>
    
    <div id="footer">
        <?php include('design/footer.php'); ?>
    </div>
</body>
</html>