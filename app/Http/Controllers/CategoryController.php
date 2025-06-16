<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'nullable|string|max:500'
        ]);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500'
        ]);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
    }
}
