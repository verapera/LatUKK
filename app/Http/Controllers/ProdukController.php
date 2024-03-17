<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    //
    public function index(){
        $produk = Produk::all();
        return view('produk.index')->with(compact('produk'));
    }
    public function create(Request $request){
        Produk::create($request->all());
        return redirect()->route('produk')->with('success','Data berhasil ditambahkan');
    }
    public function showproduk($produk_id){
        $produk = Produk::findOrFail($produk_id);
        return view('produk.update')->with(compact('produk'));
    }
    public function update(Request $request, $produk_id){
        $produk = Produk::findOrFail($produk_id);
        $produk->update($request->all());
        return redirect()->route('produk')->with('success','Data berhasil diupdate');
    }
    public function delete($produk_id){
        $produk = Produk::findOrFail($produk_id);
        $produk->delete();
        return redirect()->route('produk')->with('error','Data berhasil dihapus');
    }
}
