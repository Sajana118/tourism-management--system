<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}

// Code for deletion
if($_GET['action']=='delete') {
    $id=intval($_GET['id']);
    $sql ="DELETE FROM tblenquiry WHERE id =:id";
    $query = $dbh -> prepare($sql);
    $query -> bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $msg="Enquiry deleted successfully";
}

// Code for marking as read
if(isset($_REQUEST['eid'])) {
    $eid=intval($_GET['eid']);
    $status=1;
    $sql = "UPDATE tblenquiry SET Status=:status WHERE id=:eid";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':status',$status, PDO::PARAM_STR);
    $query-> bindParam(':eid',$eid, PDO::PARAM_STR);
    $query -> execute();
    $msg="Enquiry marked as read";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Contact Enquiries | Nepal TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../css/pages/admin-manage-contact.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">🇳🇵</div>
            <h4>Admin Panel</h4>
            <p>Nepal Tourism System</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li id="menu-academico">
                <a href="#"><i class="fas fa-list-ul" aria-hidden="true"></i><span> Tour Packages</span> <span class="fa fa-angle-right" style="margin-left: auto;"></span></a>
                <ul id="menu-academico-sub">
                    <li><a href="create-package.php"><i class="fas fa-plus-circle"></i> Create</a></li>
                    <li><a href="manage-packages.php"><i class="fas fa-edit"></i> Manage</a></li>
                </ul>
            </li>
            <li><a href="manage-bookings.php"><i class="fas fa-list"></i> <span>Manage Bookings</span></a></li>
            <li id="menu-academico"><a href="manage-users.php"><i class="fas fa-users"></i><span>Manage Users</span></a></li>
            <li><a href="manage-contact.php" class="active"><i class="fas fa-envelope"></i> <span>Contact Enquiries</span></a></li>
            <li><a href="change-password.php"><i class="fas fa-key"></i> <span>Change Password</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <h5><i class="fas fa-envelope me-2"></i>Manage Contact Enquiries</h5>
            </div>
            <div class="topbar-right">
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div>Admin</div>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="dashboard-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-envelope me-1"></i>Contact Enquiries</li>
                </ol>
            </nav>

            <!-- Alerts -->
            <?php if($error){?>
                <div class="alert alert-danger alert-custom">
                    <i class="fas fa-exclamation-circle me-2"></i><strong>ERROR:</strong> <?php echo htmlentities($error); ?>
                </div>
            <?php } else if($msg){?>
                <div class="alert alert-success alert-custom">
                    <i class="fas fa-check-circle me-2"></i><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?>
                </div>
            <?php }?>

            <!-- Contact Enquiries Table -->
            <div class="table-card">
                <div class="table-header">
                    <h5><i class="fas fa-table me-2"></i>Contact Enquiries</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact Info</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT * FROM tblenquiry ORDER BY PostingDate DESC";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            
                            if($query->rowCount() > 0) {
                                foreach($results as $result) { ?>
                                    <tr>
                                        <td>#ENQ-<?php echo htmlentities($result->id);?></td>
                                        <td><?php echo htmlentities($result->FullName);?></td>
                                        <td>
                                            <div><i class="fas fa-phone me-1"></i> <?php echo htmlentities($result->MobileNumber);?></div>
                                            <div><i class="fas fa-envelope me-1"></i> <?php echo htmlentities($result->EmailId);?></div>
                                        </td>
                                        <td><?php echo htmlentities($result->Subject);?></td>
                                        <td style="max-width: 200px;"><?php echo htmlentities($result->Description);?></td>
                                        <td><?php echo htmlentities($result->PostingDate);?></td>
                                        <td>
                                            <?php if($result->Status==1) { ?>
                                                <span class="badge-read"><i class="fas fa-check me-1"></i>Read</span>
                                            <?php } else { ?>
                                                <span class="badge-pending"><i class="fas fa-clock me-1"></i>Pending</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($result->Status==1) { ?>
                                                <a href="manage-contact.php?action=delete&&id=<?php echo $result->id;?>" 
                                                   onclick="return confirm('Do you really want to delete this enquiry?')" 
                                                   class="action-btn btn-delete">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            <?php } else { ?>
                                                <a href="manage-contact.php?eid=<?php echo htmlentities($result->id);?>" 
                                                   onclick="return confirm('Mark this enquiry as read?')" 
                                                   class="action-btn btn-read">
                                                    <i class="fas fa-eye me-1"></i>Read
                                                </a>
                                                <a href="manage-contact.php?action=delete&&id=<?php echo $result->id;?>" 
                                                   onclick="return confirm('Do you really want to delete this enquiry?')" 
                                                   class="action-btn btn-delete">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x mb-3" style="color: #cbd5e1;"></i>
                                        <h5>No contact enquiries found</h5>
                                        <p class="text-muted">Contact form submissions will appear here</p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© <?php echo date('Y'); ?> Nepal Tourism Management System. All rights reserved.</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/pages/admin-manage-contact.js"></script>
</body>
</html>
