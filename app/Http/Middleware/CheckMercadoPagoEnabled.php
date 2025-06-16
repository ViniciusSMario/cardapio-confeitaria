<?php

namespace App\Http\Middleware;

use App\Models\Configuration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMercadoPagoEnabled
{
    public function handle($request, Closure $next)
    {
        $config = Configuration::first();

        if (!$config->mercado_pago_enabled) {
            return redirect()->back()->with('error', 'O pagamento via Mercado Pago está desativado.');
        }

        return $next($request);
    }
}
