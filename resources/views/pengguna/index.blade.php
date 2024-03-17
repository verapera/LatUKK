@extends('home')
@section('content')
@section('title','Pengguna')
  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    + Pengguna
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Form tambah pengguna</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('addpengguna') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col mt-2">
                    <label for="" class="form-input">Name</label>
                    <input type="text" name="name" placeholder="Masukan nama pengguna..." class="form-control" required>
                </div>
                <div class="col mt-2">
                    <label for="" class="form-input">Username</label>
                    <input type="text" name="username" placeholder="Masukan username pengguna..." class="form-control" required>
                </div>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Password</label>
                <input type="password" name="password" placeholder="Masukan password pengguna..." class="form-control" required>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Level</label>
                <select name="level" id="" class="form-select">
                    <option value="Admin">Admin</option>
                    <option value="Petugas">Petugas</option>
                </select>
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
        <th>Name</th>
        <th>Username</th>
        <th>Level</th>
        <th>Aksi</th>
      </thead>
      <tbody>
        <tr>
          @forelse ($pengguna as $item)
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->username }}</td>
          <td>{{ $item->level}}</td>
          <td>
            <form onsubmit="return confirm('Yakin ingin menghapus data ini?')" action="{{ route('deletepengguna',$item->user_id) }}" method="POST"> 
              @csrf
              @method('DELETE')
              <button class="btn btn-danger" type="submit"> <i class="ti ti-trash"></i> </button>
              <a href="{{ route('showpengguna',$item->user_id) }}" class="btn btn-primary"> <i class="ti ti-pencil"></i> </a>
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