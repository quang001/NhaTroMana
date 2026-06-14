@extends('layouts.app')
@section('title', 'Khách thuê')

@section('breadcrumb')
    <li class="breadcrumb-item active">Khách thuê</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-700">👥 Quản lý Khách thuê</h5>
    <a href="{{ route('tenants.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Thêm khách thuê
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="search-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" name="keyword" class="form-control"
                            placeholder="Tìm theo tên, SĐT, CCCD..." value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary ms-1">X</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>CCCD/CMND</th>
                        <th>Điện thoại</th>
                        <th>Email</th>
                        <th>Nghề nghiệp</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-700"
                                    style="width:34px;height:34px;font-size:.85rem">
                                    {{ mb_substr($t->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-600">{{ $t->full_name }}</div>
                                    @if($t->gender)
                                    <small class="text-muted">{{ $t->gender == 'male' ? '♂ Nam' : '♀ Nữ' }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $t->id_card }}</code></td>
                        <td><a href="tel:{{ $t->phone }}">{{ $t->phone }}</a></td>
                        <td>{{ $t->email ?? '—' }}</td>
                        <td>{{ $t->occupation ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tenants.show', $t->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tenants.edit', $t->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('tenants.destroy', $t->id) }}"
                                    onsubmit="return confirm('Xóa khách thuê này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có khách thuê nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $tenants->withQueryString()->links() }}</div>
@endsection
