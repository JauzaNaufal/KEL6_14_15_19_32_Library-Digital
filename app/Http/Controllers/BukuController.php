<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\KategoriBuku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();
        return view('buku.index', compact('buku'));
    }

   
    public function create()
    {
        $kategori = KategoriBuku::all();
        return view('buku.create', compact('kategori'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_buku,id',
            'nama_buku' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_penerbitan' => 'required|date',
            'jumlah_tersedia' => 'required|integer|min:1',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

   
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = KategoriBuku::all();
        return view('buku.edit', compact('buku', 'kategori'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'sometimes|exists:kategori_buku,id',
            'nama_buku' => 'sometimes|string|max:255',
            'judul' => 'sometimes|string|max:255',
            'penulis' => 'sometimes|string|max:255',
            'penerbit' => 'sometimes|string|max:255',
            'tahun_penerbitan' => 'sometimes|date',
            'jumlah_tersedia' => 'sometimes|integer|min:1',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui');
    }

   
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }
}
