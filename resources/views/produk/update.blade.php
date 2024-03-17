@extends('home')
@section('content')
@section('title','Update produk')
<div class="card shadow col-md-6">
    <div class="card-body">
        <h5 class="fw-semibold"> Form update produk</h5>
        <form action="{{ route('updateproduk',$produk->produk_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col mt-2">
                    <label for="" class="form-input">Kode Produk</label>
                    <input type="text" name="kode_produk" class="form-control" value="{{ old('kode_produk',$produk->kode_produk)}}" required>
                </div>
                <div class="col mt-2">
                    <label for="" class="form-input">Nama Produk</label>
                    <input type="text" name="nama_produk" value=" {{ old('nama_produk',$produk->nama_produk)}}" class="form-control" required>
                </div>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Harga Produk</label>
                <input type="text" name="harga" value=" {{ old('harga',$produk->harga)}} " class="form-control" required>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Stok Produk</label>
                <input type="text" name="stok" value=" {{ old('stok',$produk->stok)}} " class="form-control" required>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary mt-3">Save changes</button>
            </div>
          </div>
          </form>
    </div>
</div>
@endsection