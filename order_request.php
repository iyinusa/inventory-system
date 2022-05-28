<?php
	//start session
	session_start();
	$imenu = '';
	$eform = '';
	$rec = '';
	
	if (isset($_SESSION['dname']))
	{
		$cust = $_SESSION['dname'];
		$logv = 'Welcome, <b>'.$cust.'</b> | <a href="logout.php">Log Out</a>';
		
		//connect database
		include('design/connect.php');
		
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
<link rel="stylesheet" type="text/css" href="styles/pgstyles.css"/>

<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery.pajinate.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#paging_container1').pajinate();
	});
</script>

<title>Inventory System | Order Requests</title>
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
            	<div class="topic">ADMIN > Order Requests</div>
                <div id="dash">
                	<div style="border:1px solid #900; border-bottom:none; padding:10px; background-color:#EEE;"><?php echo $logv; ?></div>
                    <div class="dash_left">
                    	<div id="iorder" class="imenu">
                        	<ul>
                            	<?php echo $imenu; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="dash_right">
                    	<div class="dr_rz">
                        	<!-- Display and Perform Update Operation -->
                            <?php
								if(isset($_GET['tag']))
								{
									$tag = $_GET['tag'];
									//if close, update button is clicked
									if(isset($_POST['btnClose']))
									{
										//redirect back to page
										header("location: order_request.php");
									} else if (isset($_POST['btnUpdate']))
									{
										$status = $_POST['status'];
										//update order table
										mysql_query("UPDATE orders SET status='$status' WHERE order_id='$tag'") or die(mysql_error());
										$eform = '<div class="msg">Update Successful</div>';
									} else
									{
										//load all product information
										$load = mysql_query("SELECT * FROM orders WHERE order_id='$tag' LIMIT 1");
										while($rows = mysql_fetch_assoc($load))
										{
											$g_pid = $rows['pdt_id'];
											$g_cid = $rows['cus_id'];
											$g_qty = $rows['quantity'];
											$g_amt = $rows['amount'];
											$g_status = $rows['status'];
											$g_req = $rows['req_date'];
											
											//get product name
											$gpdt = mysql_query("SELECT * FROM products WHERE pdt_id='$g_pid' LIMIT 1");
											while($prow = mysql_fetch_assoc($gpdt))
											{
												$g_pname = $prow['pdt_name'];
												$g_pamt = $prow['pdt_amt'];
											}
											
											//get customer name
											$gname = mysql_query("SELECT username FROM customer WHERE cus_id='$g_cid' LIMIT 1");
											while($crow = mysql_fetch_assoc($gname))
											{
												$g_cname = $crow['username'];
											}
											
											$eform = '
												<div class="msg">
													<h3>== UPDATE ORDER REQUEST ==</h3>
													<table>
														<tr>
															<td width="100px"><b>Product Name:</b></td>
															<td>
																'.$g_pname.' - ( =N= '.$g_pamt.'/Unit )
															</td>
														</tr>
														<tr>
															<td><b>Order By:</b></td>
															<td>
																'.$g_cname.'
															</td>
														</tr>
														<tr>
															<td><b>Order Quantity:</b></td>
															<td>
																'.$g_qty.'
															</td>
														</tr>
														<tr>
															<td><b>Total Amount:</b></td>
															<td>
																=N= '.$g_amt.'
															</td>
														</tr>
														<tr>
															<td><b>Current Status:</b></td>
															<td>
																'.$g_status.'
															</td>
														</tr>
													</table>
													<form action="order_request.php?tag='.$tag.'" method="post" enctype="multipart/form-data">
														<table>
															<tr>
																<td width="100px"><b>Update Status</b></td>
																<td>
																	<select name="status">
																		<option value="Pending">Pending</option>
																		<option value="Delivered">Delivered</option>
																	</select>
																</td>
															</tr>
															<tr>
																<td></td>
																<td>
																	<input type="submit" name="btnUpdate" value="Update Order" />
																	<input type="submit" name="btnClose" value="Close" />
																</td>
															</tr>
														</table>
													</form>
												</div>
											';
										}
									}
								}
							?>
                            
                            <div>
                            	<?php echo $eform; ?>
                            </div>
                            
                            <!-- Pull all Order Lists -->
                            <h2>ORDER REQUESTS HISTORY</h2>
                            <?php
                                if(isset($_SESSION['cus_id']))
                                {
                                    $g_id = $_SESSION['cus_id'];
									$g_pname = '';
                                    $get = mysql_query("SELECT * FROM orders ORDER BY order_id DESC");
                                    $get_chk = mysql_num_rows($get);
                                    if($get_chk <= 0)
                                    {
                                        $rec = '<h3 style="text-align:center;">No Order Request Yet.</h3>';	
                                    } else
                                    {
                                        while($rows = mysql_fetch_assoc($get))
                                        {
                                            $order_id = $rows['order_id'];
											$g_pid = $rows['pdt_id'];
											$g_cid = $rows['cus_id'];
                                            $g_qty = $rows['quantity'];
                                            $g_amt = $rows['amount'];
                                            $g_status = $rows['status'];
                                            $g_req = $rows['req_date'];
                                            
                                            //get product name
                                            $gpdt = mysql_query("SELECT * FROM products WHERE pdt_id='$g_pid' LIMIT 1");
                                            while($prow = mysql_fetch_assoc($gpdt))
                                            {
                                                $g_pname = $prow['pdt_name'];
                                            }
											
											//get customer name
                                            $gname = mysql_query("SELECT * FROM customer WHERE cus_id='$g_cid' LIMIT 1");
                                            while($crow = mysql_fetch_assoc($gname))
                                            {
                                                $g_cname = $crow['username'];
                                            }
                                            
                                            $rec .= '
                                                <li>
                                                    <table>
                                                        <tr>
                                                            <td width="100px">
                                                                <span style="color:#090">'.$g_req.'</span>
                                                            </td>
                                                            <td>
                                                                <b>'.$g_pname.'</b>
                                                            </td>
                                                            <td width="150px">
                                                                <b>'.$g_cname.'</b>
                                                            </td>
                                                            <td width="100px" align="center">
                                                                <a href="order_request.php?tag='.$order_id.'">View/Edit</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </li>
                                            ';
                                        }
                                    }
                                }
                            ?>
                            <div id="paging_container1" class="container">
                                <div class="page_navigation"></div>
                                <br />
                                <div class="info_text"></div>
                                
                                <table>
                                    <tr>
                                        <th width="100px">REQ. DATE</th>
                                        <th>PRODUCT NAME</th>
                                        <th width="150px">ORDER BY</th>
                                        <th width="100px">VIEW/EDIT</th>
                                    </tr>
                                </table>
                                <br />
                                <ul class="content">
                                    <?php echo $rec; ?>
                                </ul>
                                <div class="info_text"></div>
                                <div class="page_navigation"></div>
                            </div>
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