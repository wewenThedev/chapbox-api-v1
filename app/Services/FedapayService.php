<?php

namespace App\Services;

use FedaPay\Transaction;
use FedaPay\FedaPay;

class FedapayService
{
    public function __construct()
    {
        // Initialise l'API avec les clés d'accès
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.mode')); // "sandbox" ou "live"

/*        
\FedaPay\FedaPay::setApiKey('sk_sandbox_Msm0EUY1341KhUYFlNk0ZvLh');
\FedaPay\FedaPay::setEnvironment('sandbox');
*/
    }

    /**
     * Crée un paiement FedaPay et retourne le lien de paiement.
     *
     * @param float $amount Montant à payer
     * @param string $description Description du paiement
     * @param array $customer Données du client (nom, email, téléphone)
     * @return array|null
     */
    public function createPayment($amount, $description, $customer)
    {
        try {
            $transaction = Transaction::create([
                'amount' => $amount,
                'description' => $description,
                'currency' => ['iso' => config('services.fedapay.currency')],
                'customer' => $customer,
                'callback_url' => route('fedapay.callback'),
    //'callback_url' => 'https://example.com/callback',
            ]);

            return [
                'status' => 'success',
                'payment_url' => $transaction->generateToken(),
                'transaction_id' => $transaction->id
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifie le statut d'un paiement via son ID FedaPay.
     *
     * @param int $transactionId
     * @return array
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            $transaction = Transaction::retrieve($transactionId);
            return [
                'status' => $transaction->status, // "paid", "failed", "pending"
                'amount' => $transaction->amount
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}


/*

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

*/


