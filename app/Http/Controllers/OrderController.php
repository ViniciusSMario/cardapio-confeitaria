<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $phone = trim($request->input('phone'));
        $phone = preg_replace('/\D/', '', $phone); // Remove tudo que não for número

        // Verificar se o phone foi enviado
        if (!$phone && !Auth::user()) {
            return view('orders.meus_pedidos', ['orders' => null, 'phone' => null]);
        }

        if (Auth::user()) {
            $user = Auth::user();
            // Buscar pedidos do usuário autenticado

            $orders = Order::with('items')
                ->with('items.product')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            // dd($orders);
            return view('orders.meus_pedidos', compact('orders', 'phone'));
        }

        // Buscar o usuário pelo e-mail
        $user = User::where('phone', $phone)->first();

        // Se o usuário não existir, retorna mensagem
        if (!$user) {
            return view('orders.meus_pedidos', [
                'orders' => [],
                'phone' => $phone,
                'userNotFound' => true
            ]);
        }

        // Buscar pedidos do usuário autenticado
        $orders = Order::with('items')
            ->with('items.product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.meus_pedidos', compact('orders', 'phone'));
    }

    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'delivery_type' => 'required|in:retirada,delivery',
            'cep' => 'nullable|required_if:delivery_type,delivery|string|max:9',
            'rua' => 'nullable|required_if:delivery_type,delivery|string|max:255',
            'numero' => 'nullable|required_if:delivery_type,delivery|string|max:20',
            'bairro' => 'nullable|required_if:delivery_type,delivery|string|max:255',
            'cidade' => 'nullable|required_if:delivery_type,delivery|string|max:255',
            'estado' => 'nullable|required_if:delivery_type,delivery|string|max:2',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            if ($request->filled('phone')) {
                $user = User::firstOrCreate(
                    ['phone' => $request->phone],
                    ['name' => $request->name, 'email' => $request->email, 'password' => Hash::make(uniqid())]
                );
    
                Auth::login($user);
            } else {
                return redirect()->back()->with('error', 'Informe um e-mail para continuar.');
            }
        }

        $address = null;
        if ($request->delivery_type === 'delivery') {
            $address = Address::firstOrCreate([
                'user_id' => $user->id,
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
            ]);
        }

        // Criar o pedido
        $cart = json_decode($request->cart, true);
        $order = Order::create([
            'user_id' => $user->id,
            'delivery_type' => $request->delivery_type,
            'address_id' => $address?->id,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'status' => 'pendente',
            'total' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']) + ($request->shipping_cost ?? 0),
        ]);

        // Associar produtos ao pedido
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return redirect()->route('orders.success')->with('success', 'Seu pedido foi realizado com sucesso!');
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function updateStatusOld(Order $order, Request $request)
    {
        $request->validate(['status' => 'required|in:pendente,em preparo,pronto,finalizado']);
        $order->update(['status' => $request->status]);
        return redirect()->route('orders.index')->with('success', 'Status do pedido atualizado!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pendente,em preparo,pronto,finalizado'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!',
            'status' => ucfirst($order->status)
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido removido com sucesso!');
    }
}
