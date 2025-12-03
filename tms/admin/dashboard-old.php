<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
?>
<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="../assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="../assets/css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="../assets/css/morris.css" type="text/css"/>
<!-- Graph CSS -->
<link href="../assets/css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="../assets/js/js/jquery-2.1.4.min.js"></script>
<!-- //jQuery -->
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<!-- lined-icons -->
<link rel="stylesheet" href="../assets/css/icon-font.min.css" type='text/css' />
<!-- //lined-icons -->
</head> 
<body>
   <div class="page-container">
   <!--/content-inner-->
<div class="left-content">
	   <div class="mother-grid-inner">
<!--header start here-->
<?php include('includes/header.php');?>
<!--header end here-->
		<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a> <i class="fa fa-angle-right"></i></li>
            </ol>
<!--four-grids here-->
		<div class="four-grids">
			<a href="manage-users.php" target="_blank">
    <div class="col-md-4 four-grid">
        <div class="four-agileits">
            <div class="icon">
                <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
            </div>
            <div class="four-text">
                <h3>Users</h3>
                <?php $sql = "SELECT id from tblusers";
                $query = $dbh -> prepare($sql);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $cnt=$query->rowCount();
                ?>          
                <h4> <?php echo htmlentities($cnt);?> </h4>
            </div>
        </div>
    </div>
</a>

<a href="manage-packages.php" target="_blank">
    <div class="col-md-4 four-grid">
        <div class="four-wthree">
            <div class="icon">
                <i class="glyphicon glyphicon-briefcase" aria-hidden="true"></i>
            </div>
            <div class="four-text">
                <h3>Total Packages</h3>
                <?php $sql3 = "SELECT PackageId from tbltourpackages";
                $query3= $dbh -> prepare($sql3);
                $query3->execute();
                $results3=$query3->fetchAll(PDO::FETCH_OBJ);
                $cnt3=$query3->rowCount();
                ?>
                <h4><?php echo htmlentities($cnt3);?></h4>
            </div>
        </div>
    </div>
</a>

<a href="manage-bookings.php" target="_blank">
    <div class="col-md-4 four-grid">
        <div class="four-agileinfo">
            <div class="icon">
                <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
            </div>
            <div class="four-text">
                <h3>Bookings</h3>
                <?php $sql1 = "SELECT BookingId from tblbooking";
                $query1 = $dbh -> prepare($sql1);
                $query1->execute();
                $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                $cnt1=$query1->rowCount();
                ?>
                <h4><?php echo htmlentities($cnt1);?></h4>
            </div>
        </div>
    </div>
</a>

<div class="clearfix"></div>
</div>

<div class="four-grids">
    <a href="manage-bookings.php" target="_blank">
        <div class="col-md-3 four-grid">
            <div class="four-wthree" style="color:#ffc107 !important">
                <div class="icon">
                    <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
                </div>
                <div class="four-text">
                    <h3>New Bookings</h3>
                    <?php $sql ="SELECT BookingId from tblbooking where (status is null || status='')";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $newbookings=$query->rowCount();
                    ?>          
                    <h4> <?php echo htmlentities($newbookings);?> </h4>   
                </div>
            </div>
        </div>
    </a>

    <a href="manage-bookings.php" target="_blank">
        <div class="col-md-3 four-grid">
            <div class="four-agileits">
                <div class="icon">
                    <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
                </div>
                <div class="four-text">
                    <h3>Cancelled Bookings</h3>
                    <?php $sql ="SELECT BookingId from tblbooking where (status='2')";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cancelbooking=$query->rowCount();
                    ?>          
                    <h4> <?php echo htmlentities($cancelbooking);?> </h4>   
                </div>
            </div>
        </div>
    </a>

    <a href="manage-bookings.php" target="_blank">
        <div class="col-md-3 four-grid">
            <div class="four-w3ls">
                <div class="icon">
                    <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
                </div>
                <div class="four-text">
                    <h3>Confirmed Bookings</h3>
                    <?php $sql ="SELECT BookingId from tblbooking where (status='1')";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $confirmbooking=$query->rowCount();
                    ?>          
                    <h4> <?php echo htmlentities($confirmbooking);?> </h4>   
                </div>
            </div>
        </div>
    </a>

    <a href="manage-payments.php" target="_blank">
        <div class="col-md-3 four-grid">
            <div class="four-w3ls">
                <div class="icon">
                    <i class="glyphicon glyphicon-credit-card" aria-hidden="true"></i>
                </div>
                <div class="four-text">
                    <h3>Payments</h3>
                    <?php $sql ="SELECT PaymentId from tblpayment";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $payments=$query->rowCount();
                    ?>          
                    <h4> <?php echo htmlentities($payments);?> </h4>   
                </div>
            </div>
        </div>
    </a>
</div>
<div class="clearfix"></div>
<!--//four-grids here-->


<div class="inner-block">

</div>
<!--inner block end here-->
<!--copy rights start here-->
<?php include('includes/footer.php');?>
</div>
</div>

			<!--/sidebar-menu-->
				<?php include('includes/sidebarmenu.php');?>
							  <div class="clearfix"></div>		
							</div>
							<!-- Custom JS -->
							<script src="../js/pages/admin/dashboard-old.js"></script>
<!--js -->
<script src="../assets/js/js/jquery.nicescroll.js"></script>
<script src="../assets/js/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="../assets/js/js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   
<!-- morris JavaScript -->	
<script src="../assets/js/js/raphael-min.js"></script>
<script src="../assets/js/js/morris.js"></script>


	   


</body>
</html>
<?php } ?>