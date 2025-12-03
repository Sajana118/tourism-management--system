-- Add payment-related fields to tblbooking table
ALTER TABLE `tblbooking` 
ADD COLUMN `PaymentStatus` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending' AFTER `status`,
ADD COLUMN `PaymentAmount` DECIMAL(10,2) NULL AFTER `PaymentStatus`,
ADD COLUMN `PaymentTransactionId` VARCHAR(255) NULL AFTER `PaymentAmount`,
ADD COLUMN `PaymentMethod` VARCHAR(50) NULL AFTER `PaymentTransactionId`;

-- Create new table for payment transactions
CREATE TABLE IF NOT EXISTS `tblpayment` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;