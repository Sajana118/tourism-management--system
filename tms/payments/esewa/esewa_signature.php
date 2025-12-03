<?php
/**
 * eSewa Signature Generator
 * Generates HMAC SHA256 signature for eSewa payment requests
 * According to eSewa documentation:
 * - Input should be string type and the value of Signed_field_names
 * - Parameters (total_amount,transaction_uuid,product_code) should be mandatory and should be in the same order
 * - SecretKey for UAT: 8gBm/:&EnhH.1/q (WITHOUT trailing parenthesis as used in working implementation)
 * - Algorithm: SHA-256
 * - Output: Base-64 encoded
 */

function generateEsewaSignature($total_amount, $transaction_uuid, $product_code, $secret_key) {
    // Create the string to sign exactly as eSewa requires
    // Format: total_amount=<amount>,transaction_uuid=<uuid>,product_code=<code>
    $message = "total_amount=" . $total_amount . ",transaction_uuid=" . $transaction_uuid . ",product_code=" . $product_code;
    
    // Generate HMAC SHA256 signature using the secret key
    // Following the approach from the working "tourism" project
    $s = hash_hmac('sha256', $message, $secret_key, true);
    $signature = base64_encode($s);
    
    return $signature;
}

// Function to verify eSewa response signature
function verifyEsewaSignature($response_data, $secret_key) {
    // Check if required fields exist
    if (!isset($response_data['total_amount']) || !isset($response_data['transaction_uuid']) || !isset($response_data['product_code'])) {
        return false;
    }
    
    // Check if signature exists
    if (!isset($response_data['signature'])) {
        return false;
    }
    
    // Recreate the string that should have been signed
    $message = "total_amount=" . $response_data['total_amount'] . ",transaction_uuid=" . $response_data['transaction_uuid'] . ",product_code=" . $response_data['product_code'];
    
    // Generate expected signature
    $s = hash_hmac('sha256', $message, $secret_key, true);
    $expected_signature = base64_encode($s);
    
    // Compare signatures (using hash_equals for security)
    return hash_equals($expected_signature, $response_data['signature']);
}

// Debug function to test with eSewa's exact example
function testEsewaExample() {
    // Using exact values from eSewa documentation
    $total_amount = 110;
    $transaction_uuid = "241028";
    $product_code = "EPAYTEST";
    // Use the correct secret key from the working "tourism" project
    $secret_key = "8gBm/:&EnhH.1/q"; // WITHOUT the trailing parenthesis
    
    $signature = generateEsewaSignature($total_amount, $transaction_uuid, $product_code, $secret_key);
    
    return [
        'total_amount' => $total_amount,
        'transaction_uuid' => $transaction_uuid,
        'product_code' => $product_code,
        'generated_signature' => $signature,
        'expected_signature' => "i94zsd3oXF6ZsSr/kGqT4sSzYQzjj1W/waxjWyRwaME="
    ];
}

// Additional debug function to test with another example from docs
function testEsewaExample2() {
    // Using the example from the HMAC/SHA256 section
    $total_amount = 100;
    $transaction_uuid = "11-201-13";
    $product_code = "EPAYTEST";
    // Use the correct secret key from the working "tourism" project
    $secret_key = "8gBm/:&EnhH.1/q"; // WITHOUT the trailing parenthesis
    
    $signature = generateEsewaSignature($total_amount, $transaction_uuid, $product_code, $secret_key);
    
    return [
        'total_amount' => $total_amount,
        'transaction_uuid' => $transaction_uuid,
        'product_code' => $product_code,
        'generated_signature' => $signature,
        'expected_signature' => "4Ov7pCI1zIOdwtV2BRMUNjz1upIlT/COTxfLhWvVurE="
    ];
}
?>