@extends('admin.layouts.master')

@section('title', 'Tambah Item')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Data Menu</h3>
                <p class="text-subtitle text-muted">Silahkan Isi Data Menu</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Terjadi Kesalahan</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="from-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nameMenu">Nama Menu</label>
                                <input type="text" name="name" class="form-control" id="nameMenu"
                                    placeholder="Masukkan Nama Menu" required>
                            </div>

                            <div class="form-group">
                                <label for="des">Deskripsi</label>
                                <textarea name="description" class="form-control" id="des" placeholder="Masukkan Deskripsi Menu" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="number" class="form-control" id="price" placeholder="Masukkan Harga"
                                    name="price" required>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" class="form-check-input"
                                        id="flexSwitchCheckChecked" value="1" required checked>
                                    <label for="flexSwitchCheckChecked">Aktif/Tidak Aktif</label>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Gambar</label>
                                <input type="file" class="form-control" id="image" name="img" required>
                            </div>

                            <div class="form-group">
                                <label for="categoryMenu">Kategory Menu</label>
                                <select class="form-control" id="categoryMenu" name="category_id" required>
                                    <option value="" disabled selected>Pilih Kategory</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary me-1 mb-2">Simpan</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-2">Reset</button>
                                <a href="{{ route('items.index') }}" class="btn btn-primary me-1 mb-2">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
