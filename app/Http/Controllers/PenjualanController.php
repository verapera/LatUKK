<?php

namespace App\Http\Controllers;

use App\Models\Temp;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    //
    public function index(){
        $penjualan  =  Penjualan::with('pelanggans')
                    ->where('tanggal_penjualan',date('y-m-d'))
                    ->get();
        $pelanggan = Pelanggan::all();

        return view('penjualan.index')->with(compact('penjualan','pelanggan'));
    }

    public function transaksi($pelanggan_id){
        $pelanggan = Pelanggan::findOrFail($pelanggan_id);
        $produk    = Produk::where('stok','>',0)->orderBy('nama_produk','ASC')->get();
        $temp      = DB::table('temps as a')
                    ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
                    ->where('a.user_id',auth()->user()->user_id)
                    ->where('a.pelanggan_id',$pelanggan_id)
                    ->get();
        $data      = [
            'pelanggan' =>$pelanggan,
            'pelanggan_id' =>$pelanggan_id,
            'produk' =>$produk,
            'temp' =>$temp,
        ];
        return view('penjualan.transaksi',$data);
    }

    public function addtemp(Request $request, $pelanggan_id){
        $produk = Produk::where('produk_id', $request->produk_id)->first();
        $stoklama = $produk->stok;
    
        // Cek apakah produk sudah ada di keranjang
        $temp = Temp::where('user_id', auth()->user()->user_id)
                        ->where('produk_id', $request->produk_id)
                        ->where('pelanggan_id', $request->pelanggan_id)
                        ->first();
    
        if ($stoklama < $request->jumlah_produk) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        } elseif ($temp !== NULL) {
            $temp->jumlah_produk += $request->jumlah_produk;
            $temp->save();
            return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui di keranjang');
        } else {
            $data = [
                'pelanggan_id' => $request->pelanggan_id,
                'user_id' => auth()->user()->user_id,
                'produk_id' => $request->produk_id,
                'jumlah_produk' => $request->jumlah_produk,
            ];
            Temp::create($data);
            return redirect()->back()->with('success', 'Produk berhasil ditambah ke keranjang');
        }
    }
    public function deltemp($temp_id){
        $temp = Temp::findOr($temp_id);
        $temp->delete();
        return redirect()->back()->with('error','Data berhasil dihapus');
    }

    public function bayarr(Request $request,$pelanggan_id){
        $jumlah    = Penjualan::whereRaw("DATE_FORMAT(tanggal_penjualan,'%Y-%m')=?",[date('Y-m')])->count();
        $nota      = date('ymd').($jumlah+1);
        $temp      = DB::table('temps as a')
                    ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
                    ->where('a.user_id',auth()->user()->user_id)
                    ->where('a.pelanggan_id',$pelanggan_id)
                    ->get();

        foreach($temp as $item){
            if ($item->stok < $item->jumlah_produk) {
                # code...
                return redirect()->back()->with('error','Stok tidak mencukupi');
            }
            $data = [
                'kode_penjualan'=> $nota,
                'produk_id'=> $item->produk_id,
                'subtotal'=> $item->jumlah_produk*$item->harga,
                'jumlah_produk'=> $item->jumlah_produk,
            ];
            DetailPenjualan::create($data);
            // update
            Produk::where('produk_id',$item->produk_id)
                    ->update(['stok'=>$item->stok-$item->jumlah_produk]);
        } 
        Penjualan::create([
            'kode_penjualan'=>$nota,
            'tanggal_penjualan'=>date('y-m-d'),
            'pembayaran'=>$request->pembayaran,
            'total_harga'=>$request->total_harga,
            'pelanggan_id'=>$request->pelanggan_id,
        ]);
        // delete tempory
        Temp::where('pelanggan_id', $pelanggan_id)
         ->where('user_id', auth()->user()->user_id)
         ->delete();
        return redirect()->route('invoice',$nota)->with('success','Transaksi success!'); 

    }
    public function invoice($kode_penjualan){
        $penjualan  =  DB::table('penjualans as a')
                    ->orderBy('tanggal_penjualan','DESC')
                    ->leftJoin('pelanggans as b','a.pelanggan_id','=','b.pelanggan_id')
                    ->where('kode_penjualan',$kode_penjualan)
                    ->first();
        $detail    = DB::table('detail_penjualans as a')
                    ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
                    ->where('kode_penjualan',$kode_penjualan)
                    ->get();
        $nota       = $kode_penjualan;
        $data       =[
            'detail' => $detail,
            'penjualan' => $penjualan,
            'nota' => $nota,
        ];
        return view('penjualan.invoice',$data);
    }
    public function report($kode_penjualan){
        $detail    = DB::table('detail_penjualans as a')
                    ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
                    ->where('kode_penjualan',$kode_penjualan)
                    ->get();
        $penjualan  =  DB::table('penjualans as a')
                    ->leftJoin('pelanggans as b','a.pelanggan_id','=','b.pelanggan_id')
                    ->where('kode_penjualan',$kode_penjualan)
                    ->first();

        TCPDF::SetTitle('Invoice |' . $penjualan->kode_penjualan);
        TCPDF::AddPage();
        TCPDF::SetFont('helvetica','B','12');
        TCPDF::Cell(0,10,'                        MagooKasir',0,1,'L');
        
        TCPDF::SetFont('helvetica','','10');
        TCPDF::Cell(0,7,'                                     Pusat',0,1,'L');
        TCPDF::Cell(0,7,'   Jl.Derpoyudho, Mojogedang,KM 05, Karanganyar',0,1,'L');
        TCPDF::Cell(0,7,'                          Hp. 089672284196',0,1,'L');
        TCPDF::Cell(0,7,' =======================================',0,1,'L');

        TCPDF::Cell(0,6,$penjualan->tanggal_penjualan.'                                                #'.$penjualan->kode_penjualan,0,1,'L');
        TCPDF::Cell(0,6,'User          = '.auth()->user()->username,0,1,'L');
        TCPDF::Cell(0,6,'Pelanggan = '.$penjualan->nama_pelanggan,0,1,'L');

        TCPDF::Cell(0,7,' ---------------------------------------------------------------------',0,1,'L');
        TCPDF::Cell(10,7,'No',0,0,'L');
        TCPDF::Cell(20,7,'Kd Brg',0,0,'L');
        TCPDF::Cell(10,7,'Qty',0,0,'L');
        TCPDF::Cell(20,7,'Harga',0,0,'L');
        TCPDF::Cell(35,7,'Subtotal',0,0,'L');
        TCPDF::Cell(0,7,'',0,1,'L');
        TCPDF::Cell(0,7,' ---------------------------------------------------------------------',0,1,'L');
        $no = 1;
        foreach($detail as $item){
            TCPDF::Cell(10,7,$no++,0,0,'L');
            TCPDF::Cell(20,7,$item->kode_produk,0,0,'L');
            TCPDF::Cell(10,7,$item->jumlah_produk,0,0,'L');
            TCPDF::Cell(20,7,'Rp.'.number_format($item->harga),0,0,'L');
            TCPDF::Cell(35,7,'Rp.'.number_format($item->subtotal),0,0,'L'); 
            TCPDF::Cell(0,7,'',0,1,'L');
            TCPDF::Cell(0,7,'Brg = '.$item->nama_produk,0,1,'L');
        }
        TCPDF::Cell(0,7,' =======================================',0,1,'L');
        TCPDF::Cell(0,7,'Total           = '.'Rp.'.number_format($penjualan->total_harga),0,1,'L');
        TCPDF::Cell(0,7,'Bayar          = '.'Rp.'.number_format($penjualan->pembayaran),0,1,'L');
        TCPDF::Cell(0,7,'Kembalian  = '.'Rp.'.number_format($penjualan->pembayaran-$penjualan->total_harga),0,1,'L');
        TCPDF::Cell(0,7,' ---------------------------------------------------------------------',0,1,'L');

        TCPDF::Output('invoice.pdf','I');

        
    }
}
