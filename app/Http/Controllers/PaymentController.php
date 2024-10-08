<?php

namespace App\Http\Controllers;

use Braintree\Gateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
     // Mostra il form di pagamento
    public function showPaymentForm(Gateway $gateway)
    {

        $clientToken = $gateway->clientToken()->generate();

        return view('payment', compact('clientToken'));
    }

     // Elabora il pagamento
    public function processPayment(Request $request, Gateway $gateway)
{
    $nonce = $request->payment_method_nonce;
    $amount = $request->amount;

    // Crea la transazione
    $result = $gateway->transaction()->sale([
        'amount' => $amount, // importo personalizzato (swag)
        'paymentMethodNonce' => $nonce,
        'options' => [
            'submitForSettlement' => true
        ]
    ]);

    if ($result->success) {
        return redirect()->route('payment.form')->with('success', 'Pagamento completato con successo!');
    } else {
        return redirect()->route('payment.form')->with('error', 'Errore durante il pagamento: ' . $result->message);
    }
}

}