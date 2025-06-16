<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    public function index() {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : null;

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $imagePath
        ]);

        return redirect()->route('products.index')->with('success', 'Produto adicionado com sucesso!');
    }

    public function edit(Product $product) {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $product->image);
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->only(['name', 'description', 'price', 'category_id']));

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product) {
        Storage::delete('public/' . $product->image);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto removido com sucesso!');
    }
}
