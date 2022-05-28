<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="styles/lay.css"/>

<title>Inventory System | Register</title>
</head>

<body>
	<div id="all">
    	<div id="header">
        	<div id="hd_rz">
            	<?php include('design/header.php') ?>
            </div>
        </div>
        
        <div id="sitecont">
        	<div class="hm_flash">
            	<?php include('design/hmflash.php'); ?>
            </div>
            <br />
            <div id="sc_rz">
            	<div class="topic">Inventory Optimization System | Dangote Group</div>
                <div class="home">
                	<div class="home_right">
                    	
                    </div>
                    <div class="home_left">
						<div class="topic">Register</div>
                        <div>
                        	<!-- Registration Code -->
                            <?php
								$reg_msg = '';
								
								if (isset($_POST['btnRegister']))
								{
									//connect database
									include ('design/connect.php');
									$uname = $_POST['uname'];
									$upass = $_POST['upass'];
									$ucpass = $_POST['ucpass'];
									$uemail = $_POST['uemail'];
									
									//check validation
									if (!$uname || !$upass || !$ucpass || !$uemail)
									{
										$reg_msg = '<div class="msg">All fields are required</div>';
									} else if ($upass != $ucpass) {
										$reg_msg = '<div class="msg">Password not matched</div>';	
									} else {
										//check if customer username exist
										$cname = mysql_query("SELECT * FROM customer WHERE username='$uname'");
										$cname_chk = mysql_num_rows($cname);
										if ($cname_chk > 0)
										{
											$reg_msg = '<div class="msg">Username already exists, please choose another</div>';
										} else {
											//hash password
											$hash = md5($upass);
											mysql_query("INSERT INTO customer (username,password,email,role,reg_date)VALUES('$uname','$hash','$uemail','Customer',now())") or die(mysql_error());
											$reg_msg = '<div class="msg"><b>Thanks for registering! - please <a href="index.php">Click Here</a> to Log In</div>';
										}
									}
								}
							?>
                            
                            <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                            	<fieldset>
                                	<legend>Customer Registration</legend>
                                    <?php echo $reg_msg; ?>
                                    Already a Registered Customer?, kindly <a href="index.php">Click Here</a> to <b>Log In</b> or <b>Register</b> below<br /><br />
                                    Username:<br />
                                    <input type="text" name="uname" /><br /><br />
                                    Password:<br />
                                    <input type="password" name="upass" /><br /><br />
                                    Confirm Password:<br />
                                    <input type="password" name="ucpass" /><br /><br />
                                    Email Address:<br />
                                    <input type="text" name="uemail" /><br /><br />
                                    <input type="submit" name="btnRegister" value="Log In" />
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