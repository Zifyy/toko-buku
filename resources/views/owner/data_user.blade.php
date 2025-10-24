@extends('owner.layout.owner_layout')

@section('title', 'Kelola User')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-2 text-2xl font-bold text-gray-800">Kelola User</h1>
        <p class="mb-4 text-gray-600">Atur akun Admin dan Kasir di sistem toko 📋</p>

        <!-- Alert Success/Error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Data User Card -->
        <div class="card shadow-sm">
            <div class="card-header text-white d-flex justify-content-between align-items-center"
                 style="background: linear-gradient(90deg, #00c6ff, #0077b6);">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>Data User
                </h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah User
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th width="120">Role</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + $users->firstItem() }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info text-white ms-1">Anda</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($user->role === 'admin') bg-primary 
                                            @elseif($user->role === 'owner') bg-success 
                                            @else bg-warning text-dark 
                                            @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            {{-- Hanya bisa edit user yang bukan owner, atau diri sendiri (tapi tidak bisa ubah role) --}}
                                            @if($user->role !== 'owner' || $user->id === auth()->id())
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editUserModal{{ $user->id }}"
                                                        title="Edit User">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @else
                                                <!-- Tidak bisa edit owner lain -->
                                                <button class="btn btn-secondary btn-sm" 
                                                        disabled 
                                                        title="Tidak bisa edit owner lain">
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            @endif

                                            {{-- Hanya bisa hapus user yang bukan diri sendiri dan bukan owner lain --}}
                                            @if($user->id !== auth()->id() && $user->role !== 'owner')
                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('owner.user.destroy', $user->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm"
                                                            title="Hapus User">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" 
                                                        disabled 
                                                        title="{{ $user->id === auth()->id() ? 'Tidak bisa hapus akun sendiri' : 'Tidak bisa hapus owner lain' }}">
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit User -->
                                @if($user->role !== 'owner' || $user->id === auth()->id())
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header text-white"
                                                     style="background: linear-gradient(90deg, #f1c40f, #f39c12);">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil-square me-2"></i>Edit User
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('owner.user.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                                                            <input type="text" 
                                                                   name="name" 
                                                                   class="form-control @error('name') is-invalid @enderror" 
                                                                   value="{{ old('name', $user->name) }}" 
                                                                   required>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                            <input type="email" 
                                                                   name="email" 
                                                                   class="form-control @error('email') is-invalid @enderror" 
                                                                   value="{{ old('email', $user->email) }}" 
                                                                   required>
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                                            @if($user->id === auth()->id())
                                                                {{-- Jika edit diri sendiri, role tidak bisa diubah --}}
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       value="{{ ucfirst($user->role) }}" 
                                                                       readonly>
                                                                <input type="hidden" name="role" value="{{ $user->role }}">
                                                                <small class="text-muted">
                                                                    <i class="bi bi-info-circle"></i> Tidak dapat mengubah role ini
                                                                </small>
                                                            @else
                                                                {{-- Jika edit user lain, bisa pilih admin atau kasir saja --}}
                                                                <select name="role" 
                                                                        class="form-select @error('role') is-invalid @enderror" 
                                                                        required>
                                                                    <option value="">-- Pilih Role --</option>
                                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                                    <option value="kasir" {{ $user->role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                                </select>
                                                                @error('role')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Password Baru (Opsional)</label>
                                                            <input type="password" 
                                                                   name="password" 
                                                                   class="form-control @error('password') is-invalid @enderror" 
                                                                   placeholder="Kosongkan jika tidak ingin mengubah">
                                                            @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <small class="text-muted">Minimal 6 karakter</small>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                                                            <input type="password" 
                                                                   name="password_confirmation" 
                                                                   class="form-control" 
                                                                   placeholder="Ulangi password baru">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i>Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-warning">
                                                            <i class="bi bi-check-circle me-1"></i>Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data user
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white"
                     style="background: linear-gradient(90deg, #00c851, #007e33);">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah User Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('owner.user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <small>Kamu hanya dapat menambahkan user dengan role <strong>Admin</strong> atau <strong>Kasir</strong></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}"
                                   placeholder="contoh@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                            <select name="role" 
                                    class="form-select @error('role') is-invalid @enderror" 
                                    required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Role Owner tidak dapat ditambahkan
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 6 karakter"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ulangi password"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Tambah User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto dismiss alert setelah 5 detik
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Jika ada error, buka modal yang sesuai
    @if($errors->any())
        @if(old('_method') === 'PUT')
            // Buka modal edit jika ada error saat update
            const editModalId = 'editUserModal{{ old('user_id') }}';
            const editModalEl = document.getElementById(editModalId);
            if(editModalEl) {
                const editModal = new bootstrap.Modal(editModalEl);
                editModal.show();
            }
        @else
            // Buka modal tambah jika ada error saat create
            const addModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            addModal.show();
        @endif
    @endif
</script>
@endpush