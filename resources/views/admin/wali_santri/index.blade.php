@extends('layouts.app')
@section('title', 'Data Wali Santri')
@section('page-title', 'Data Wali Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Wali Santri</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Wali Santri</h5>
        <a href="{{ route('admin.wali-santri.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Wali
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Wali</th>
                        <th>Pekerjaan</th>
                        <th>No HP</th>
                        <th>Akun User</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($walis as $wali)
                        <tr>
                            <td>{{ $loop->iteration + $walis->firstItem() - 1 }}</td>
                            <td>
                                <strong>{{ $wali->user->name }}</strong>
                            </td>
                            <td>{{ $wali->pekerjaan ?? '-' }}</td>
                            <td>{{ $wali->telepon ?? '-' }}</td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-envelope"></i> {{ $wali->user->email }}
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.wali-santri.edit', $wali->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.wali-santri.destroy', $wali->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data wali santri ini? Data user juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Data wali santri tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($walis->hasPages())
        <div class="card-footer">
            {{ $walis->links() }}
        </div>
    @endif
</div>
@endsection
