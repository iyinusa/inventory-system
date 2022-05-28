<?php
	//start session
	session_start();
	$imenu = '';
	
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

<title>Inventory System | Manage Products</title>
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
            	<div class="topic">ADMIN > Manage Products</div>
                <div id="dash">
                	<div style="border:1px solid #900; border-bottom:none; padding:10px; background-color:#EEE;"><?php echo $logv; ?></div>
                    <div class="dash_left">
                    	<div id="iprod" class="imenu">
                        	<ul>
                            	<?php echo $imenu; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="dash_right">
                    	<div class="dr_rz">
                        	<!-- Hide and Show Add Product -->
                            <?php
								$addpdt = '';
								$rec = '';
								if (isset($_GET['action']))
								{
									$action = $_GET['action'];
									if ($action == "add")
									{
										if (isset($_POST['btnClose']))
										{
											//close form and redirect to page
											header("location: manage_products.php");
										} else if (isset($_POST['btnAdd']))
										{
											$pname = $_POST['nname'];
											$pnumber = $_POST['nstock'];
											$plevel = $_POST['nlevel'];
											$pamount = $_POST['namount'];
											
											//check validation
											if (!$pname || !$pnumber || !$pamount)
											{
												$addpdt = '<div class="msg">
													<a href="manage_products.php?action=add">ADD NEW PRODUCT(S)</a><br /><br />
													ERROR: All fields are required
												</div>';	
											} else
											{
												//check if product already exists
												$pdt = mysql_query("SELECT * FROM products WHERE pdt_name='$pname' LIMIT 1");
												$pdt_chk = mysql_num_rows($pdt);
												if ($pdt_chk > 0)
												{
													//update product information
													mysql_query("UPDATE products SET pdt_name='$pname',pdt_no='$pnumber',pdt_level='$plevel',pdt_amt='$pamount',upd_date=now() WHERE pdt_name='$pname'") or die(mysql_error());
													$addpdt = '<div class="msg">
														<a href="manage_products.php?action=add">ADD NEW PRODUCT(S)</a><br /><br />
														DONE: Product Updated Successfully
													</div>';	
												} else
												{
													//save new product information
													mysql_query("INSERT INTO products (pdt_name,pdt_no,pdt_amt,pdt_level,upd_date) VALUES ('$pname','$pnumber','$plevel','$pamount',now())") or die(mysql_error());	
													$addpdt = '<div class="msg">
														<a href="manage_products.php?action=add">ADD NEW PRODUCT(S)</a><br /><br />
														DONE: Product Added Successfully
													</div>';
												}
											}
										} else
										{
											$addpdt = '<div class="msg">
												<form method="post" action="manage_products.php?action=add" enctype="multipart/form-data">
													<fieldset>
														<legend>Add More Product</legend>
														Product Name:<br />
														<input type="text" name="nname" /><br /><br />
														Numbers in Stock:<br />
														<input type="text" name="nstock" />&nbsp;(i.e. 20)<br /><br />
														Re-Order Level:<br />
														<input type="text" name="nlevel" />&nbsp;(i.e. 100)<br /><br />
														Unit Price (=N=):<br />
														<input type="text" name="namount" />&nbsp;(i.e. 120)<br /><br />
														<input type="submit" name="btnAdd" value="Add Product" />&nbsp;
														<input type="submit" name="btnClose" value="Close" />
													</fieldset>
												</form>
											</div>';
										}
									} else if ($action == "edit")
									{
										if (isset($_GET['item']))
										{
											$item = $_GET['item'];
											$pullp = mysql_query("SELECT * FROM products WHERE pdt_id='$item'");
											
											while ($rowp=mysql_fetch_assoc($pullp))
											{
												$name = $rowp['pdt_name'];
												$no = $rowp['pdt_no'];
												$level = $rowp['pdt_level'];
												$amt = $rowp['pdt_amt'];
												
												$addpdt = '<div class="msg">
													<form method="post" action="manage_products.php?action=add" enctype="multipart/form-data">
														<fieldset>
															<legend>Add More Product</legend>
															Product Name:<br />
															<input type="text" name="nname" value="'.$name.'" /><br /><br />
															Numbers in Stock:<br />
															<input type="text" name="nstock" value="'.$no.'" />&nbsp;(i.e. 20)<br /><br />
															Re-Order Level:<br />
															<input type="text" name="nlevel" value="'.$level.'" />&nbsp;(i.e. 100)<br /><br />
															Unit Price (=N=):<br />
															<input type="text" name="namount" value="'.$amt.'" />&nbsp;(i.e. 120)<br /><br />
															<input type="submit" name="btnAdd" value="Add Product" />&nbsp;
															<input type="submit" name="btnClose" value="Close" />
														</fieldset>
													</form>
												</div>';
											}
										}
									} else if ($action == "delete")
									{
										if (isset($_GET['item']))
										{
											$item = $_GET['item'];
											//delete product
											mysql_query("DELETE FROM products WHERE pdt_id='$item'") or die(mysql_error());
											$addpdt = '<div class="msg">
													<a href="manage_products.php?action=add">ADD NEW PRODUCT(S)</a><br /><br />
													DONE: Product Removed Successfully
												</div>';
										}
									}
								} else
								{
									$addpdt = '<div class="msg"><a href="manage_products.php?action=add">ADD NEW PRODUCT(S)</a></div>';
								}
							?>
							
							<?php echo $addpdt; ?>
                            <br />
                            <!-- Pull all Products -->
                            <?php
								$pull = mysql_query("SELECT * FROM products ORDER BY pdt_id DESC");
								$pull_chk = mysql_num_rows($pull);
								if ($pull_chk <= 0)
								{
									$rec = '<h3 style="text-align:center;">No Product In Stock Yet.</h3>';	
								} else
								{
									while ($row=mysql_fetch_assoc($pull))
									{
										$id = $row['pdt_id'];
										$name = $row['pdt_name'];
										$no = $row['pdt_no'];
										$amt = $row['pdt_amt'];
										$date = $row['upd_date'];
										
										$rec .= '<li>
											<table>
												<tr>
													<td width="60px" align="center">
														<a href="manage_products.php?action=delete&amp;item='.$id.'">Delete</a>
													</td>
													<td width="90px">
														<span style="color:#090">'.$date.'</span>
													</td>
													<td width="400px">
														<b>'.$name.'</b>
													</td>
													<td width="80px">
														<span style="color:#090; font-weight:bold;">'.$no.'</span>
													</td>
													<td width="100px">
														<b>'.$amt.'</b>
													</td>
													<td width="60px" align="center">
														<a href="manage_products.php?action=edit&amp;item='.$id.'">Edit</a>
													</td>
												</tr>
											</table>
										</li>';
									}
								}
							?>
                            
                            <div id="paging_container1" class="container">
                                <div class="page_navigation"></div>
                                <br />
                                <div class="info_text"></div>
                                
                                <table>
                                    <tr>
                                        <th width="60px">DELETE</th>
                                        <th width="120px">UPD. DATE</th>
                                        <th width="400px">PRODUCT NAME</th>
                                        <th width="80px">NO</th>
                                        <th width="100px">AMT/UNIT(=N=)</th>
                                        <th width="60px">EDIT</th>
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