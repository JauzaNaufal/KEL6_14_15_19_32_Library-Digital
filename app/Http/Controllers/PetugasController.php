<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;


class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::all();
        return view('petugas.index', compact('petugas'));
    }

    
    public function create()
    {
        return view('petugas.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'nomor_telepon' => 'required|numeric',
            'email' => 'required|email|unique:petugas',
        ]);

        Petugas::create($request->all());

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    
    public function show($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.show', compact('petugas'));
    }

    
    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.edit', compact('petugas'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'sometimes|string|max:255',
            'posisi' => 'sometimes|string|max:255',
            'nomor_telepon' => 'sometimes|numeric',
            'email' => 'sometimes|email|unique:petugas,email,' . $id,
        ]);

        $petugas = Petugas::findOrFail($id);
        $petugas->update($request->all());

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diperbarui');
    }

    /**
     * Menghapus data petugas dari database.
     */
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus');
    }
}
