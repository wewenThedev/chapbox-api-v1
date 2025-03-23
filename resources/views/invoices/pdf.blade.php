<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { padding: 30px; border: 1px solid #ddd; }
        h2 { color: #ff4500; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Facture #{{ $invoice->invoice_number }}</h2>
        <p>Date : {{ $invoice->created_at->format('d/m/Y') }}</p>
        <p>Client : {{ $payment->user->firstname }} {{ $payment->user->lastname }}</p>
        <p>Supermarché : Erevan</p> 
        <p>Commande en ligne n° : 0095</p>
        <p>Moyen de paiement : Paiement via {{ $payment->payment_method }} MTN MOMO FedaPay</p>


        <table>
            <tr>
                <th>Description</th>
                <th>Montant</th>
                <th>Devise</th>
            </tr>
            <tr>
                
                <td>
               <p>Huile de ricin 916 FCFA (458 * 2 unités)</p>
               <p>Huile de ricin 916 FCFA (458 * 2 unités)</p>
               <p>Huile de ricin 916 FCFA (458 * 2 unités)</p>
            </td>
                <td>{{ number_format($invoice->amount, 2) }}</td>
                <td>{{ $invoice->currency }}</td>
            </tr>
        </table>
        <p><strong>Total : {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</strong></p>
    </div>
</body>
</html>
