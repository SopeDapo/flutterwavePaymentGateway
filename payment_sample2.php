<?php
//note you are to use one of the file this or payment.php 
// choose anyone that you think it works for you.
// Replace with your own values
$public_key = 'YOUR_PUBLIC_KEY';
$secret_key = 'YOUR_SECRET_KEY';
$amount = 1000; // Amount in kobo
$currency = 'NGN';
$txref = 'YOUR_TRANSACTION_REFERENCE';
$redirect_url = 'https://example.com/payment/success';

// Build request body
$body = array(
    'amount' => $amount,
    'currency' => $currency,
    'txref' => $txref,
    'redirect_url' => $redirect_url,
    'payment_options' => 'card',
    'meta' => array(
        'consumer_id' => 23,
        'consumer_mac' => '92a3-912ba-1192a'
    ),
    'customer' => array(
        'email' => 'test@example.com',
        'phone_number' => '08100000000',
        'name' => 'John Doe'
    ),
    'customizations' => array(
        'title' => 'My Payment Title',
        'description' => 'My Payment Description',
        'logo' => 'https://example.com/logo.png'
    )
);

// Generate authorization header
$timestamp = time();
$signature = hash_hmac('sha256', $public_key . $timestamp . '', $secret_key);
$authorization = 'Bearer ' . $signature;

// Make API request
$ch = curl_init('https://api.flutterwave.com/v3/payments');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: ' . $authorization,
    'Timestamp: ' . $timestamp
));
$response = curl_exec($ch);
curl_close($ch);

// Redirect to payment page
$response_data = json_decode($response, true);
if ($response_data['status'] == 'success') {
    $payment_url = $response_data['data']['link'];
    header('Location: ' . $payment_url);
    exit;
} else {
    // Handle error
    echo 'Error: ' . $response_data['message'];
}
?>
