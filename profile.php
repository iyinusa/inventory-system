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

<title>Inventory System | Profile</title>
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
            	<div class="topic">Customers > Profile</div>
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
                            <h3><a href="eprofile.php">Update Profile</a></h3>
                            <!-- Pull Customer Profile -->
                            <?php 
                                //connect database
								include("design/connect.php");
								if (isset($_SESSION['dname']))
								{
									$uid = $_SESSION['cus_id'];
									$uname = $_SESSION['dname'];
									$ufull = '';
									$uaddress = '';
									$uphone = '';
									
									//pull customer information
									$pull = mysql_query("SELECT * FROM customer WHERE cus_id='$uid'");
									while($row=mysql_fetch_assoc($pull))
									{
										$ureg = $row['reg_date'];
										$upass = $row['password'];
										$uemail = $row['email'];
										$ufull = $row['fullname'];
										$uphone = $row['phone'];
										$uaddress = $row['address'];
									}
								}
								
                            ?>
                            
                            <fieldset>
                                <legend>Authentication Information</legend>
                                <table>
                                	<tr>
                                    	<td width="120px"><b>Registered On:</b></td>
                                        <td><?php echo $ureg; ?></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Username:</b></td>
                                        <td><?php echo $uname; ?></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Password:</b></td>
                                        <td><?php echo $upass; ?> - <i>[Hidden]</i></td>
                                    </tr>
                                </table>
                            </fieldset>
                            
                            <br />
                            
                            <fieldset>
                                <legend>Contact Information</legend>
                                <table>
                                	<tr>
                                    	<td width="120px"><b>Full Name:</b></td>
                                        <td><?php echo $ufull; ?></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Email Address:</b></td>
                                        <td><?php echo $uemail; ?></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Phone:</b></td>
                                        <td><?php echo $uphone; ?></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Address:</b></td>
                                        <td><?php echo $uaddress; ?></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </div>
                
               <!-- <div class="topic">Our Brands</div>
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