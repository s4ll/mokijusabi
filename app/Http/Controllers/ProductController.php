<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::when($request->search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('pages.product', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('components.product.create-modal', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Bersihkan format price (misal dari "Rp 10.000" -> 10000)
        $request->merge([
            'price' => str_replace('.', '', preg_replace('/[^0-9.]/', '', $request->price))
        ]);

        $request->validate([
            'name' => 'required|string' ,
            'price' => 'required|numeric' ,
            'stock' => 'required|integer|min:0' ,
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' ,
        ]);

        $imagePath = null ;
        $request->hasFile('image');
        $imagePath = $request->file('image')->store('products', 'public');

        try {
            Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $imagePath,
            ]);
            return redirect()->route('product.index')->with('success', 'Product add successfully!');
        } catch (\Exception $error) {
            return redirect()->route('product.index')->with('error', 'Failed to create product.' . $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $products =  Product::findOrFail($id);
        return view('components.product.update-modal', compact('products'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'integer|min:0',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'stock' => $request->stock ,
        ]);

        return redirect()->route('product.index')->with('success', 'Stock updated successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Bersihkan format price (misal dari "Rp 10.000" -> 10000)
        $request->merge([
            'price' => str_replace('.', '', preg_replace('/[^0-9.]/', '', $request->price))
        ]);

        $request->validate([
            'name' => 'string' ,
            'price' => 'numeric' ,
            'stock' => 'integer|min:0' ,
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048' ,
        ]);

        $product = Product::findOrFail($id);

        // Cek apakah ada file gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Simpan gambar baru dan hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image); // Hapus gambar lama
            }
            $imagePath = $request->file('image')->store('products', 'public'); // Simpan gambar baru
        } else {
            $imagePath = $product->image; // Gunakan gambar lama jika tidak diubah
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
    }
}
