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

<title>Inventory System | Requests</title>
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
            	<div class="topic">Customers > Requests</div>
                <div id="dash">
                	<div style="border:1px solid #900; border-bottom:none; padding:10px; background-color:#EEE;"><?php echo $logv; ?></div>
                    <div class="dash_left">
                    	<div id="imsg" class="imenu">
                        	<ul>
                            	<?php echo $imenu; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="dash_right">
                    	
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