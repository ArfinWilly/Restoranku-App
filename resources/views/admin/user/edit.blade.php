@extends('admin.layouts.master')

@section('title', 'Edit Karyawan')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data Karyawan</h3>
                <p class="text-subtitle text-muted">Silahkan Isi Data Karyawan</p>
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
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="from-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username"
                                    placeholder="Masukkan Username" value="{{ $user->username }}" required>
                            </div>

                            <div class="form-group">
                                <label for="fullname">Nama Karyawan</label>
                                <input type="text" name="fullname" class="form-control" id="fullname"
                                    placeholder="Masukkan Nama Karyawan" value="{{ $user->fullname }}" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Masukkan Password">
                                <small><a href="#" class="toggle-password" data-target="password">Show</a></small>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="password_confirmation" placeholder="Konfirmasi Password">
                                <small><a href="#" class="toggle-password"
                                        data-target="password_confirmation">Show</a></small>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="Masukkan Email" value="{{ $user->email }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Nomor HP</label>
                                <input type="text" class="form-control" id="phone_number" placeholder="Masukkan Nomor HP"
                                    name="phone" value="{{ $user->phone }}" required>
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role_id" required>
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->role_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary me-1 mb-2">Simpan</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-2">Reset</button>
                                <a href="{{ route('users.index') }}" class="btn btn-primary me-1 mb-2">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                let input = document.getElementById(this.dataset.target);
                let hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';

                document.querySelector(`a[data-target="${this.dataset.target}"]`).textContent = hidden ?
                    'Hide' : 'Show';

                // Cara kedua
                // querySelectorAll('.toggle-password').forEach(function (el) {
                //     el.textContent = hidden ? 'Hide' : 'Show';
                // });

                //Cara ketiga
                // const target = this.getAttribute('data-target');
                // const input = document.getElementById(target);
                // if (input.type === 'password') {
                //     input.type = 'text';
                //     this.textContent = 'Hide';
                // } else {
                //     input.type = 'password';
                //     this.textContent = 'Show';
                // }
            });
        });
    </script>
@endsection
