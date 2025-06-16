<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago;

class CheckoutController extends Controller
{
    public function processarPagamento(Request $request)
    {
        // Configurar credenciais do Mercado Pago
        \MercadoPago\SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));

        // Criar um objeto de pagamento
        $payment = new \MercadoPago\Payment();
        $payment->transaction_amount = (float) $request->input('amount'); // Valor do pagamento
        $payment->token = $request->input('token'); // Token gerado pelo Mercado Pago
        $payment->description = $request->input('description', 'Compra no site'); // Descrição da compra
        $payment->installments = (int) $request->input('installments', 1); // Parcelas
        $payment->payment_method_id = $request->input('payment_method_id'); // Método de pagamento
        $payment->payer = [
            "email" => $request->input('email')
        ];

        // Salvar pagamento
        $payment->save();

        // Verificar status do pagamento
        if ($payment->status == 'approved') {
            return redirect()->route('checkout.success')->with('success', 'Pagamento aprovado!');
        } else {
            return redirect()->route('checkout.fail')->with('error', 'Pagamento falhou! Tente novamente.');
        }
    }
}

