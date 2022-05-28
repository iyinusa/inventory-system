<?php
	//start session
	session_start();
	$imenu = '';
	$rec = '';
	
	if (isset($_SESSION['dname']))
	{
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
<link rel="stylesheet" type="text/css" href="styles/pgstyles.css"/>

<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/jquery.pajinate.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#paging_container1').pajinate();
	});
</script>

<title>Inventory System | Shopping Cart</title>
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
            	<div class="topic">Customers > Shopping Chart</div>
                <div id="dash">
                	<div style="border:1px solid #900; border-bottom:none; padding:10px; background-color:#EEE;"><?php echo $logv; ?></div>
                    <div class="dash_left">
                    	<div id="icart" class="imenu">
                        	<ul>
                            	<?php echo $imenu; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="dash_right">
                    	<div class="dr_rz">
                        	<h2>ORDER PRODUCT(S)</h2>
                            <div>
                            	<?php 
									//pull products
									$pdt = '';
									$order = '';
									$order_msg = '';
									
									$list = mysql_query("SELECT * FROM products");
									$list_chk = mysql_num_rows($list);
									if ($list_chk <= 0)
									{
										$pdt = '<option>No Product Yet</option>';	
									} else
									{
										while($row = mysql_fetch_assoc($list))
										{
											$pdt_id = $row['pdt_id'];
											$pdt_name = $row['pdt_name'];
											$pdt_no = $row['pdt_no'];
											$pdt_level = $row['pdt_level'];
											
											$pdt .= '<option value="'.$pdt_id.'">'.$pdt_name.'</option>';
										}
									}
									
									if (isset($_POST['btnChoose']))
									{
										if (isset($_POST['sel_pdt']))
										{
											$sel_id = $_POST['sel_pdt'];
											$res = mysql_query("SELECT * FROM products WHERE pdt_id='$sel_id' LIMIT 1");
											$res_chk = mysql_num_rows($res);
											if ($res_chk <= 0)
											{
												$order = '<option>This Product has been removed</option>';	
											} else
											{
												while($row = mysql_fetch_assoc($res))
												{
													$pdtid = $row['pdt_id'];
													$pdt_name = $row['pdt_name'];
													$pdt_no = $row['pdt_no'];
													$pdt_amt = $row['pdt_amt'];
													
													//create cart session
													//session_register('$pdtid');
													//session_register('$pdt_name');
													//session_register('$pdt_no');
													//session_register('$pdt_amt');
													$_SESSION['$pdtid'] = $pdtid;
													$_SESSION['$pdt_name'] = $pdt_name;
													$_SESSION['$pdt_no'] = $pdt_no;
													$_SESSION['$pdt_amt'] = $pdt_amt;
													
													$order = '
														<div style="background-color:#EEE; margin:15px; padding:10px; text-shadow:1px 0px 1px #FFF; color:Maroon;">
															<div style="font-size:large; margin-bottom:10px; border-bottom:1px solid #000;">
																'.$pdt_name.' - '.$pdt_no.' available in stock ( =N='.$pdt_amt.'/Unit )
															</div>
															<div>
																No. of Quantity:&nbsp;
																<input type="text" name="pdtno" />&nbsp;(i.e. 20)&nbsp;
																<input type="submit" name="btnOrder" value="Order Product" />
															</div>
														</div>
													';
												}
											}
										}
									}
									
									//save order
									if (isset($_POST['btnOrder']))
									{
										$pdtno = $_POST['pdtno'];
										//check validation
										if (!$pdtno)
										{
											$order_msg = '<div class="msg">You must supply quantity of item to order</div>';
										} else
										{
											//check if order more than amount in stock
											$stock_no = $_SESSION['$pdt_no'];
											if ($pdtno > $stock_no)
											{
												$order_msg = '<div class="msg"><h3 style="text-align:center;">You can not order more than '.$stock_no.' for now<h3></div>';
											} else
											{
												//calculate order
												$stock_amt = $_SESSION['$pdt_amt'];
												$amt = $pdtno * $stock_amt;
												$rest = $stock_no - $pdtno;
												
												$order_msg  = '<div class="msg">
													<div style="background-color:#CCC; padding: 10px; margin-bottom:10px; font-size:large;">
														ORDER CONFIRMATION
													</div>
													<div style="padding:5px; font-size:medium;">
														Total Amount: =N= '.$amt.'
														<br /><br />
														<input type="submit" name="btnConfirm" value="Confirm Order" />&nbsp;
														<input type="submit" name="btnCancel" value="Cancel" />
													</div>
												</div>';
												
												//session_register('qt');
												//session_register('t_amt');
												//session_register('rest');
												$_SESSION['qt'] = $pdtno;
												$_SESSION['t_amt'] = $amt;
												$_SESSION['rest'] = $rest;
											}
										}
									}
									
									//confirm order
									if (isset($_POST['btnConfirm']))
									{
										//get product sessions
										$pid = $_SESSION['$pdtid'];
										$cid = $_SESSION['cus_id'];
										$qt = $_SESSION['qt'];
										$t_amt = $_SESSION['t_amt'];
										$rest = $_SESSION['rest'];
										
										//save new order
										if(mysql_query("UPDATE products SET pdt_no='$rest' WHERE pdt_id='$pid' LIMIT 1")) {
											mysql_query("INSERT INTO orders (pdt_id,cus_id,quantity,amount,status,req_date) VALUES ('$pid','$cid','$qt','$t_amt','Pending',now())") or die(mysql_error());
											$order_msg = '<div class="msg">Order Requested Successfully</div>';
										}
									}
								?>
                              	<form method="post" action="cart.php" enctype="multipart/form-data">
                                    Select Product:&nbsp;
                                    <select name="sel_pdt">
                                        <?php echo $pdt; ?>
                                    </select>&nbsp;
                                    <input type="submit" name="btnChoose" value="Done" />
                               	</form>
                                
                                <form method="post" action="cart.php" enctype="multipart/form-data">
                                    <?php echo $order_msg; ?>
									<?php echo $order; ?>
                               	</form>
                                
                                <hr />
                                
                                <!-- Pull all Cart Lists -->
                                <h2>MY SHOPPING CART HISTORY</h2>
                                <?php
									if(isset($_SESSION['cus_id']))
									{
										$g_id = $_SESSION['cus_id'];
										$g_pname = '';
										$get = mysql_query("SELECT * FROM orders WHERE cus_id='$g_id' ORDER BY order_id DESC");
										$get_chk = mysql_num_rows($get);
										if($get_chk <= 0)
										{
											$rec = '<h3 style="text-align:center;">You do not have any History yet.</h3>';	
										} else
										{
											while($rows = mysql_fetch_assoc($get))
											{
												$g_pid = $rows['pdt_id'];
												$g_qty = $rows['quantity'];
												$g_amt = $rows['amount'];
												$g_status = $rows['status'];
												$g_req = $rows['req_date'];
												
												//get product name
												$gpdt = mysql_query("SELECT * FROM products WHERE pdt_id='$g_pid'");
												while($prow = mysql_fetch_assoc($gpdt))
												{
													$g_pname = $prow['pdt_name'];
												}
												
												$rec .= '
													<li>
														<table>
															<tr>
																<td width="120px">
																	<span style="color:#090">'.$g_req.'</span>
																</td>
																<td width="370px">
																	<b>'.$g_pname.'</b>
																</td>
																<td width="80px">
																	<span style="color:#090; font-weight:bold;">'.$g_qty.'</span>
																</td>
																<td width="100px">
																	<b>'.$g_amt.'</b>
																</td>
																<td width="80px" align="center">
																	<b>'.$g_status.'</b>
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
                                            <th width="120px">REQ. DATE</th>
                                            <th width="370px">PRODUCT NAME</th>
                                            <th width="80px">QTY.</th>
                                            <th width="100px">AMT/UNIT(=N=)</th>
                                            <th width="80px">STATUS</th>
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