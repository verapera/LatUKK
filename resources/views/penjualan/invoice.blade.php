@extends('home')
@section('content')
@section('title','Invoice')
<div class="card shadow">
    <div class="card-body">
        <h3>Invoice {{ $penjualan->tanggal_penjualan }}</h3>
        <div class="row">
            <div class="col-md-4">
                <span>From :</span>
                <p>
                    MagooKasir <br>
                    Jl.Derpoyudho, Mojogedang,KM 05, Karanganyar <br>
                    089672284196
                </p>
            </div>
            <div class="col-md-4">
                <span>To :</span>
                <p>
                    {{ $penjualan->nama_pelanggan }} <br>
                    {{ $penjualan->alamat }} <br>
                    {{ $penjualan->nomor_telepon }} <br>
                </p>
            </div>
            <div class="col-md-4">
                <h3>#{{$nota  }}</h3>
            </div>
        </div>
      <div class="table-responsive text-nowrap ">
        <table class="table table-bordered">
            <thead>
              <th>No</th>
              <th>Kode produk</th>
              <th>Nama produk</th>
              <th>Quantity</th>
              <th>Harga</th>
              <th>Subtotal</th>
            </thead>
            <tbody>
              <tr>
                  <?php $total = 0; ?>
                  @forelse ($detail as $item)
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $item->kode_produk }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->jumlah_produk }}</td>
                    <td>Rp.{{ number_format($item->harga) }}</td>
                    <td>Rp.{{ number_format($item->harga*$item->jumlah_produk)}}</td>
              </tr>
              <?php $total+=$item->harga*$item->jumlah_produk; ?>
                @empty
                    <div class="alert alert-danger">
                      Data belum tersedia
                    </div>
                @endforelse
                @if (session('success'))
                    <div class="alert alert-success">
                      {{ (session('success'))}}
                    </div>
                @elseif (session('error'))
                <div class="alert alert-danger">
                  {{ (session('error'))}}
                </div>
                @endif
                <tr>
                  <td colspan="5">Total harga</td>
                  <td colspan="5">Rp.{{number_format($total ) }}</td>
                </tr>
                <tr>
                  <td colspan="5">Pembayaran</td>
                  <td colspan="5">Rp.{{number_format($penjualan->pembayaran ) }}</td>
                </tr>
                <tr>
                  <td colspan="5">Kembalian</td>
                  <td colspan="5">Rp.{{number_format($penjualan->pembayaran-$total ) }}</td>
                </tr>
            </tbody>
        </table>
        <a href="{{ route('report',$item->kode_penjualan) }}" class="btn btn-danger">Cetak</a>
        </div>
    </div>
    </div>

@endsection