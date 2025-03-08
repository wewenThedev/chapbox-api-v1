<?php
use FedaPay;

FedaPay\FedaPay::getApiKey();

//require_once('vendor/autoload.php');
require_once('../../vendor/autoload.php');

\FedaPay\FedaPay::setApiKey('sk_sandbox_Msm0EUY1341KhUYFlNk0ZvLh');

\FedaPay\FedaPay::setEnvironment('sandbox');

//Créer un client
\FedaPay\Customer::create(array(
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john.doe@gmail.com',
    'phone_number' => [
        'number' => '+22966666600',
        'country' => 'bj' // 'bj' Benin code
    ]
));

$orderId=52;

$transaction = \FedaPay\Transaction::create([
    'description' => 'Payment for order '.$orderId,
    'amount' => 1000,
    'currency' => ['iso' => 'XOF'],
    'callback_url' => 'https://example.com/callback',
    'mode' => 'mtn_open',
    'customer' => [
        'id' => 1,
        'device_id' => ''
    ],
]);


