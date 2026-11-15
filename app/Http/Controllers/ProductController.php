<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // READ : Display all products
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    // CREATE : Show Product Creation Form
    public function create()
    {
        return view('products.create');
    }

    // STORE : Store New Product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    // SHOW : Display a single product
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Edit: show Edit form
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update: update product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()
        ->route('products.index')
        ->with('success', 'Product updated successfully!');
    }


    // Delete: delete product
    public function destroy(Product $product)

    {
        $product->delete();
        return redirect()
        ->route('products.index')
        ->with('success', 'Product deleted successfully!');
    }

}
