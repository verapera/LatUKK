@extends('home')
@section('content')
@section('title','Pelanggan')
  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    + Pelanggan
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Form tambah pelanggan</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('addpelanggan') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col mt-2">
                    <label for="" class="form-input">Nama pelanggan</label>
                    <input type="text" name="nama_pelanggan" placeholder="Masukan nama pelanggan..." class="form-control" required>
                </div>
                <div class="col mt-2">
                    <label for="" class="form-input">Alamat</label>
                    <input type="text" name="alamat" placeholder="Masukan alamat..." class="form-control" required>
                </div>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Nomor telepon</label>
                <input type="text" name="nomor_telepon" placeholder="Masukan nomor_telepon..." class="form-control" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </div>
          </form>
        </div>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-bordered shadow">
      <thead style="background-color:rgb(226, 240, 253) ">
        <th>No</th>
        <th>Nama pelanggan</th>
        <th>Alamat</th>
        <th>Nomor Telepon</th>
        <th>Aksi</th>
      </thead>
      <tbody>
        <tr>
          @forelse ($pelanggan as $item)
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nama_pelanggan }}</td>
          <td>{{ $item->alamat}}</td>
          <td>{{ $item->nomor_telepon}}</td>
          <td>
            <form onsubmit="return confirm('Yakin ingin menghapus data ini?')" action="{{ route('deletepelanggan',$item->pelanggan_id) }}" method="POST"> 
              @csrf
              @method('DELETE')
              <button class="btn btn-danger" type="submit"> <i class="ti ti-trash"></i> </button>
            </form>
          </td>
        </tr>
          @empty
              <div class="alert alert-danger">
                Data tidak tersedia
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
      </tbody>
    </table>
  </div>
@endsection