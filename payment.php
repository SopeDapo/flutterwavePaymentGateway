<?php

// Step 1: Set up your Flutterwave API keys and base URL
$public_key = 'your_public_key';
$secret_key = 'your_secret_key';
$base_url = 'https://api.flutterwave.com/v3';

// Step 2: Create the payment payload
$payload = array(
    'tx_ref' => uniqid('tx_ref_', true),
    'amount' => 100,
    'currency' => 'NGN',
    'redirect_url' => 'https://your-redirect-url.com/complete-payment',
    'payment_options' => 'card',
    'meta' => array(
        'user_id' => 23,
        'product_id' => 46,
    ),
    'customer' => array(
        'email' => 'user@example.com',
        'phone_number' => '08160413494',
        'name' => 'Sopdap Tech',
    ),
    'customizations' => array(
        'title' => 'My Company Name',
        'description' => 'Payment for item purchased on my company website',
        'logo' => 'https://your-company-logo-url.com/logo.png',
    ),
);

// Step 3: Create the payment URL
$payment_url = $base_url.'/payments';

// Step 4: Set the headers
$headers = array(
    'Authorization: Bearer '.$secret_key,
    'Content-Type: application/json',
);

// Step 5: Send the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $payment_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

// Step 6: Handle the response
$response_data = json_decode($response, true);
if ($response_data['status'] == 'success') {
    // Payment was successful, redirect the user to the payment page
    header('Location: '.$response_data['data']['link']);
} else {
    // Payment failed, display an error message
    echo 'Payment failed: '.$response_data['message'];
}
//if you need assistance reach me via the above sample phone number
?>
