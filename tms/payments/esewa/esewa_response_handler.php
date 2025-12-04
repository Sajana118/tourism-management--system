<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../config/database.php');
include('esewa_signature.php');

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header('location: ../../index.php#login');
    exit();
}

// eSewa configuration
$product_code = 'EPAYTEST'; // For testing, change to your actual product code in production
$secret_key = '8gBm/:&EnhH.1/q'; // UAT secret key - WITHOUT trailing parenthesis

// Handle eSewa response (sent as POST data)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log the incoming data for debugging
    error_log("eSewa Response Received: " . print_r($_POST, true));
    
    // Get the Base64 encoded response data
    $encoded_data = $_POST['data'] ?? '';
    
    if (!empty($encoded_data)) {
        // Decode the Base64 response
        $decoded_data = base64_decode($encoded_data);
        $response_data = json_decode($decoded_data, true);
        
        error_log("Decoded eSewa Response: " . print_r($response_data, true));
        
        if ($response_data && is_array($response_data)) {
            // Verify the signature if it exists
            if (isset($response_data['signature']) && 
                isset($response_data['total_amount']) && 
                isset($response_data['transaction_uuid']) && 
                isset($response_data['product_code'])) {
                
                // Verify signature
                $is_valid = verifyEsewaSignature($response_data, $secret_key);
                
                if ($is_valid) {
                    // Signature is valid, process the payment
                    if (isset($response_data['status']) && $response_data['status'] === 'COMPLETE') {
                        // Extract transaction details
                        $transaction_uuid = $response_data['transaction_uuid'];
                        $ref_id = $response_data['ref_id'] ?? '';
                        $total_amount = $response_data['total_amount'];
                        
                        // Extract booking ID from transaction UUID (format: booking_id-timestamp)
                        $parts = explode('-', $transaction_uuid);
                        $booking_id = $parts[0];
                        
                        // Validate booking ID
                        if (is_numeric($booking_id)) {
                            // Update payment status to completed
                            $sql = "UPDATE tblpayment SET PaymentStatus = 'completed', TransactionId = :transaction_id WHERE BookingId = :booking_id";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':transaction_id', $ref_id, PDO::PARAM_STR);
                            $query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
                            $result = $query->execute();
                            
                            // Update booking payment status
                            $booking_sql = "UPDATE tblbooking SET PaymentStatus = 'completed', PaymentTransactionId = :transaction_id, PaymentMethod = 'eSewa' WHERE BookingId = :booking_id";
                            $booking_query = $dbh->prepare($booking_sql);
                            $booking_query->bindParam(':transaction_id', $ref_id, PDO::PARAM_STR);
                            $booking_query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
                            $booking_result = $booking_query->execute();
                            
                            if ($result && $booking_result) {
                                // Set success message
                                $_SESSION['msg'] = "Payment completed successfully! Your booking is now confirmed.";
                            } else {
                                $_SESSION['error'] = "Payment completed but failed to update records. Please contact support.";
                            }
                        } else {
                            $_SESSION['error'] = "Invalid booking ID in transaction. Please contact support.";
                        }
                    } else {
                        // Payment not completed
                        $_SESSION['error'] = "Payment was not completed. Status: " . ($response_data['status'] ?? 'Unknown');
                    }
                } else {
                    // Invalid signature - possible fraud
                    $_SESSION['error'] = "Invalid payment signature. Please contact support.";
                    error_log("Invalid eSewa signature. Response: " . print_r($response_data, true));
                }
            } else {
                // Missing required fields
                $_SESSION['error'] = "Missing required payment data. Please contact support.";
                error_log("Missing required fields in eSewa response: " . print_r($response_data, true));
            }
        } else {
            // Failed to decode JSON
            $_SESSION['error'] = "Invalid payment response format. Please contact support.";
            error_log("Failed to decode eSewa response. Data: " . $encoded_data);
        }
    } else {
        // No data received
        $_SESSION['error'] = "No payment data received. Please contact support.";
        error_log("No data received from eSewa. POST: " . print_r($_POST, true));
    }
} else {
    // Not a POST request
    $_SESSION['error'] = "Invalid request method.";
    error_log("Invalid request method to esewa_response_handler: " . $_SERVER['REQUEST_METHOD']);
}

// Redirect to tour history page
header('location: ../../tour-history.php');
exit();
?>