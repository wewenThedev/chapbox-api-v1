<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

use FedaPay\FedaPay;
use FedaPay\Transaction;

use App\Services\FedapayService;


///v1 class
class PaymentController extends Controller
{
    protected $fedaPay;

    public function __construct()
    {
        $this->fedaPay = new FedaPay(config('services.fedapay.public_key'), config('services.fedapay.secret_key'));
    }

    /**
     * Display a listing of the resource.
     */
    //lister tous les paiements
    public function index()
    {
        //
        //return Payment::all();
        return Payment::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    //enregistrer un paiement
    public function store(StorePaymentRequest $request)
    {
        //
        //$payment = Payment::create($request->validated());
        $payment = Payment::create($request->toArray());
        return response()->json($payment, 201);

    }

    /**
     * Display the specified resource.
     */
    //afficher un paiement
    public function show(string $id /*Payment $payment*/)
    {
        $payment = Payment::findOrFail($id);
        return $payment;
    }

    /**
     * Update the specified resource in storage.
     */
    //mettre à jour un paiement
    public function update(UpdatePaymentRequest $request, string $id /*Payment $payment*/)
    {
        $payment = Payment::findOrFail($id);
        

        $payment->update($request->validated());
        return response()->json($payment);

    }

    public function updatePaymentStatus(string $id, $paymentSuccess){

        $payment = Payment::findOrFail($id);
        $order = Order::findOrFail($payment->order_id);
        // Si le paiement réussit, mettre à jour le statut
if ($paymentSuccess) {
    $payment->update(['status' => 'success']);
    $order->update(['status' => 'paid']);
} else {
    $payment->update(['status' => 'failed']);
}
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(null, 204);

    }

    public function processPayment(Request $request)
    {
        $amount = $request->input('amount');
        $phone = $request->input('phone');
        
        // Crée une nouvelle transaction
        $transaction = $this->fedaPay->transaction->create([
            'amount' => $amount,
            'phone' => $phone,
            'currency' => 'XOF', // Exemple de devise
            'description' => 'Payment description',
        ]);

        if ($transaction->status == 'success') {
            return response()->json(['success' => true, 'transaction_id' => $transaction->id], 200);
        } else {
            return response()->json(['success' => false, 'message' => $transaction->message], 400);
        }
    }

    public function generatePaymentToken()
    {
        // Génération d'un token pour le frontend (si nécessaire)
        $token = $this->fedaPay->token->create();
        return response()->json(['token' => $token]);
    }


}

//v2
/*
class PaymentController extends Controller
{
    protected $fedapayService;

    public function __construct(FedapayService $fedapayService)
    {
        $this->fedapayService = $fedapayService;
    }

    /**
     * Génère un paiement et retourne l'URL de paiement à l'utilisateur.
     *
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'description' => 'required|string',
            'customer' => 'required|array',
            'customer.email' => 'required|email',
            'customer.firstname' => 'required|string',
            'customer.lastname' => 'required|string',
            'customer.phone' => 'required|string'
        ]);

        $response = $this->fedapayService->createPayment(
            $request->amount,
            $request->description,
            $request->customer
        );

        return response()->json($response);
    }

    /**
     * Vérifie le statut d'un paiement.
     *
    public function getPaymentStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|integer'
        ]);

        $response = $this->fedapayService->checkPaymentStatus($request->transaction_id);

        return response()->json($response);
    }
}
*/
