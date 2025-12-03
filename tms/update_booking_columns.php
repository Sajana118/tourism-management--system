<?php
// Database update script for tblbooking table
include('config/database.php');

// Only show output if specifically requested
if (isset($_GET['debug'])) {
    echo "<h2>Updating tblbooking table structure...</h2>";
}

try {
    // Add new columns to tblbooking table for booking details
    $sql = "ALTER TABLE tblbooking 
            ADD COLUMN Persons INT DEFAULT 1,
            ADD COLUMN Guide TINYINT(1) DEFAULT 0,
            ADD COLUMN Vehicle TINYINT(1) DEFAULT 0";
    
    $dbh->exec($sql);
    if (isset($_GET['debug'])) {
        echo "<p style='color: green;'>✓ Successfully added booking detail columns to tblbooking table</p>";
    }
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        if (isset($_GET['debug'])) {
            echo "<p>Booking detail columns already exist in tblbooking table.</p>";
        }
    } else {
        if (isset($_GET['debug'])) {
            echo "<p style='color: red;'>Error adding booking detail columns: " . $e->getMessage() . "</p>";
        }
    }
}

try {
    // Add payment-related fields to tblbooking table
    $sql1 = "ALTER TABLE `tblbooking` 
             ADD COLUMN `PaymentStatus` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending' AFTER `status`,
             ADD COLUMN `PaymentAmount` DECIMAL(10,2) NULL AFTER `PaymentStatus`,
             ADD COLUMN `PaymentTransactionId` VARCHAR(255) NULL AFTER `PaymentAmount`,
             ADD COLUMN `PaymentMethod` VARCHAR(50) NULL AFTER `PaymentTransactionId`";
    
    $dbh->exec($sql1);
    if (isset($_GET['debug'])) {
        echo "<p style='color: green;'>✓ Successfully added payment fields to tblbooking table</p>";
    }
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        if (isset($_GET['debug'])) {
            echo "<p>Payment fields already exist in tblbooking table.</p>";
        }
    } else {
        if (isset($_GET['debug'])) {
            echo "<p style='color: red;'>Error adding payment fields to tblbooking table: " . $e->getMessage() . "</p>";
        }
    }
}

try {
    // Create new table for payment transactions
    $sql2 = "CREATE TABLE IF NOT EXISTS `tblpayment` (
              `PaymentId` int(11) NOT NULL AUTO_INCREMENT,
              `BookingId` int(11) NOT NULL,
              `UserId` int(11) NOT NULL,
              `Amount` decimal(10,2) NOT NULL,
              `PaymentMethod` varchar(50) NOT NULL,
              `TransactionId` varchar(255) NULL,
              `PaymentStatus` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
              `PaymentDate` timestamp NULL DEFAULT current_timestamp(),
              `Remarks` text NULL,
              PRIMARY KEY (`PaymentId`),
              FOREIGN KEY (`BookingId`) REFERENCES `tblbooking`(`BookingId`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
    
    $dbh->exec($sql2);
    if (isset($_GET['debug'])) {
        echo "<p style='color: green;'>✓ Successfully created tblpayment table</p>";
    }
} catch (PDOException $e) {
    if (isset($_GET['debug'])) {
        echo "<p style='color: red;'>Error creating tblpayment table: " . $e->getMessage() . "</p>";
    }
}

// Only show completion message if debugging
if (isset($_GET['debug'])) {
    echo "<p>Database update completed successfully.</p>";
    echo "<p><a href='index.php'>Back to Home</a></p>";
} else {
    // Silent success
    header('location: index.php');
    exit();
}
?>
?>