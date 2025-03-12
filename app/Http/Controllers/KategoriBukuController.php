<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;

class KategoriBukuController extends Controller
{
    public function index()
    {
        $kategori = KategoriBuku::all();
        return view('kategori.index', compact('kategori'));
    }

    
    public function create()
    {
        return view('kategori.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriBuku::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

     function show($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    
    public function edit($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = KategoriBuku::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    
    public function destroy($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
