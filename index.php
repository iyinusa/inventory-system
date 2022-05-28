<?php
	//start session
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="styles/lay.css"/>

<title>Inventory System</title>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
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
            	<div class="topic">Inventory Optimization System</div>
                <hr />
                <div class="home">
                	<div class="home_right">
                    	
                    </div>
                    <div class="home_left">
                      <div class="topic">Login</div>
                        
                        <!-- Login Code -->
                        <?php
							$log_msg = '';
							
							if (isset($_POST['btnLogin']))
							{
								//connect database
								include ("design/connect.php");
								$uname = $_POST['uname'];
								$upass = $_POST['upass'];
								
								//check validation
								if (!$uname || !$upass)
								{
									$log_msg = '<div class="msg">All fields are required</div>';
								} else {
									//un-hash password
									$hash = md5($upass); 
									//check authentication
									$auth = mysql_query("SELECT * FROM customer WHERE (username='$uname' OR email='$uname') AND password='$hash' LIMIT 1") or die(mysql_error());
									
									$auth_chk = mysql_num_rows($auth);
									if ($auth_chk <= 0)
									{
										$log_msg = '<div class="msg">Authentication Failed! - try again</div>';
									} else {
										while ($row = mysql_fetch_assoc($auth))
										{
											$cus_id = $row['cus_id'];
											$uname = $row['username'];	
											$role = $row['role'];
											
											//register and store session
											//session_register('cus_id');
											//session_register('dname');
											//session_register('drole');
											$_SESSION['cus_id'] = $cus_id;
											$_SESSION['dname'] = $uname;
											$_SESSION['drole'] = $role;
											
											//redirect customer
											if ($role == "Admin")
											{
												$link = 'dashboard.php';
												header('location: dashboard.php');
											} else
											{
												$link = 'profile.php';
												header('location: profile.php');
											}
											
											$log_msg = '<div class="msg">Login Successful! - <a href="http://localhost/inventory/'.$link.'">Click to Proceed</a></div>';
										}
									}
								}
							}
						?>
                        
                        <div>
                        	<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                            	<fieldset>
                                	<legend>Customer Login</legend>
                                    <?php echo $log_msg; ?>
                                    Already a Registered Customer?, kindly login below or <a href="register.php">Click Here</a> to <b>Register</b><br /><br />
                                    Username/Email:<br />
                                    <input type="text" name="uname" /><br /><br />
                                    Password:<br />
                                    <input type="password" name="upass" /><br /><br />
                                    <input type="submit" name="btnLogin" value="Log In" />
                                </fieldset>
                            </form>
                        </div>
                  </div>
                </div>
                <!--
                <div class="topic">Our Brands</div>
                <div id="b_brands">
                	<?php include('design/brands.php'); ?>
                </div>-->
            </div>
        </div>
    </div>
    
    <div id="footer">
        <?php include("design/footer.php"); ?>
    </div>
</body>
</html>