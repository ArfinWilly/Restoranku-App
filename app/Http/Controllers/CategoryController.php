<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all() ;
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'cat_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'cat_name.required' => 'Nama kategory wajib diisi.',
        ]);

        Category::create($validate);

        return redirect()->route('categories.index')->with('success', 'Data Kategory Berhasil Ditambahkan.');
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
        $category = Category::findOrFail($id) ;
        return view('admin.category.edit' , compact('category')) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'cat_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'cat_name.required' => 'Nama kategory wajib diisi.',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validate);

        return redirect()->route('categories.index')->with('success', 'Data Kategory Berhasil Diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Data Kategory Berhasil Dihapus.');
    }
}
