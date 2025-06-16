<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::take(3)->get();
        $featuredProducts = Product::latest()->take(4)->get();
        return view('home', compact('featuredProducts', 'categories'));
    }

    public function categories()
    {
        $categories = Category::all();
        return view('categories', compact('categories'));
    }

    public function orderSuccess()
    {
        return view('orders.success');
    }

    public function shop(Request $request, $category_id = null)
    {
        // Obtém os filtros da requisição
        $category_id = $request->input('category_id') ?? $category_id;
        $search = $request->input('search');
    
        // Inicia a query dos produtos
        $query = Product::with('category');
    
        // Aplica o filtro por categoria, se houver
        if ($category_id) {
            $query->where('category_id', $category_id);
        }
    
        // Aplica o filtro por nome, se houver
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        // Paginação dos produtos
        $products = $query->paginate(9);
    
        // Busca todas as categorias para o filtro
        $categories = Category::all();
    
        return view('shop', compact('products', 'categories'));
    }    

    public function checkout()
    {
        $addresses = Auth::check() ? Auth::user()->addresses : [];
        return view('checkout', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        //
    }
}
