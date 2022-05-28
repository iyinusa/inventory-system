<?php
	//start session
	session_start();
	$imenu = '';
	
	if (isset($_SESSION['dname']))
	{
		$drole = $_SESSION['drole'];
		if($drole != "Admin")
		{
			header("location: profile.php");	
		}
		
		$cust = $_SESSION['dname'];
		$logv = 'Welcome, <b>'.$cust.'</b> | <a href="logout.php">Log Out</a>';
		
		//connect database
		include("design/connect.php");
		
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

<title>Inventory System | Dashboard</title>
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
            	<div class="topic">Admin > Dashboard</div>
                <div id="dash">
                	<div style="border:1px solid #900; border-bottom:none; padding:10px; background-color:#EEE;"><?php echo $logv; ?></div>
                    <div class="dash_left">
                    	<div id="idash" class="imenu">
                        	<ul>
                            	<?php echo $imenu; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="dash_right">
                    	<div class="dr_rz">
                        	
                            
                            <!-- Product Levels -->
                            <?php
								$pdt = '';
								$list = mysql_query("SELECT * FROM products");
								$list_chk = mysql_num_rows($list);
								$width = 500/$list_chk;
								if ($list_chk <= 0)
								{
									$pdt = '<div class="msg">No Product Yet</div>';	
								} else
								{
									while($row = mysql_fetch_assoc($list))
									{
										$pdt_id = $row['pdt_id'];
										$pdt_name = $row['pdt_name'];
										$pdt_no = $row['pdt_no'];
										$pdt_level = $row['pdt_level'];
										
										$bar = $pdt_no/10;
										
										//check if product reaches reorder level
										if ($pdt_no <= $pdt_level)
										{
											$pdt .= '<li>
												<table width="'.$width.'">
													<tr>
														<td height="'.$bar.'" style="background-color:Green; color:#FFF; text-align:center;"><span style="text-decoration:blink;">'.$pdt_no.'</span><br /><span style="color: Pink;">'.$pdt_level.'</td>
													</tr>
													<tr>
														<td style="font-size:xx-small; color:Maroon; text-align:center;">'.$pdt_name.'</td>
													</tr>
												</table>
											</li>'; 

										} else
										{
											$pdt .= '<li>
												<table width="'.$width.'">
													<tr>
														<td height="'.$bar.'" style="background-color:Navy; color:#FFF; text-align:center;">'.$pdt_no.'<br /><span style="color: Pink;">'.$pdt_level.'</span></td>
													</tr>
													<tr>
														<td style="font-size:xx-small; color:Maroon; text-align:center;">'.$pdt_name.'</td>
													</tr>
												</table>
											</li>'; 
										}
									}
								}
							?>
                            <div class="pdt_level">
                            	<div class="pl_hd">
                                	Products Level
                                </div>
                                <div>
                                	<b><u>KEYS:</u></b><br /><br />
                                    <div style="background-color:Navy; height:15px; width:15px;"></div>&nbsp;
                                    = Current Product Level<br />
                                    <div style="background-color:Green; height:15px; width:15px;"></div>&nbsp;
                                    = Re-Order Level Reached<br /><br />
                                    <b>Numbers in White Colour</b> = Product Total in Stock<br />
                                    <b>Numbers in Pink Colour</b> = Re-Order Level of Product
                                </div>
                                <br />
                                <ul>
                                   	<?php echo $pdt; ?>
                               	</ul>
                            </div>
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