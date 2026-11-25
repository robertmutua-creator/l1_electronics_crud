<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(?int $id = null): View
    {
        $products = Product::paginate(10);
        $product = $id ? Product::findOrFail($id) : null;
        $title = $product ? 'Edit Product' : 'Electronics Products';
        return view('products', compact('products', 'title', 'product'));
    }

    public function store(Request $request, ?int $id = null): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $validated['name'] = Str::title($validated['name']);
        $validated['brand'] = Str::title($validated['brand']);

        if ($id) {
            Product::findOrFail($id)->update($validated);
        } else {
            Product::create($validated);
        }
        return redirect('/')->with('success', 'Operation completed successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id)->delete();
        return redirect('/')->with('success', 'Operation completed successfully!');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,ods',
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect('/')->with('success', 'Products imported successfully');
    }
}
