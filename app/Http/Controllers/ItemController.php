<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::orderBy('name' , 'asc')->get();
        return view('admin.item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('cat_name', 'asc')->get();

        return view('admin.item.create', compact('categories')) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ] , 
        [
            'name.required' => 'Nama menu wajib diisi.',
            'description.required' => 'Deskripsi menu wajib diisi.',
            'price.required' => 'Harga menu wajib diisi.',
            'is_active.required' => 'Status menu wajib dipilih.',
            'is_active.boolean' => 'Status menu wajib berupa true atau false.',
            'img.required' => 'Gambar menu wajib dipilih.',
            'img.max' => 'Gambar menu maksimal berukuran 2048 kilobytes.',
            'category_id.required' => 'Kategory menu wajib dipilih.',
        ]);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time().'.'.$image->getClientOriginalExtension();  
            $image->move(public_path('img_item_upload'), $imageName);
            $validate['img'] = $imageName;
        }

        $item = Item::create($validate);

        return redirect()->route('items.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::orderBy('cat_name', 'asc')->get();

        return view('admin.item.edit', compact('item', 'categories')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find id
        $item = Item::findOrFail($id);

        // Validate request
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ] , 
        [
            'name.required' => 'Nama menu wajib diisi.',
            'description.required' => 'Deskripsi menu wajib diisi.',
            'price.required' => 'Harga menu wajib diisi.',
            'is_active.required' => 'Status menu wajib dipilih.',
            'is_active.boolean' => 'Status menu wajib berupa true atau false.',
            'img.required' => 'Gambar menu wajib dipilih.',
            'img.max' => 'Gambar menu maksimal berukuran 2048 kilobytes.',
            'category_id.required' => 'Kategory menu wajib dipilih.',
        ]);

        // Handle image upload
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time().'.'.$image->getClientOriginalExtension();  
            $image->move(public_path('img_item_upload'), $imageName);
            $validate['img'] = $imageName;
        }

        // Update item
        $item->update($validate);

        // Redirect with success message
        return redirect()->route('items.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find id and delete
        $item = Item::findOrFail($id);
        $item->delete();

        // Redirect with success message
        return redirect()->route('items.index')->with('success', 'Menu berhasil dihapus.');
    }
}
