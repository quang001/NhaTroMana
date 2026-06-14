@extends('layouts.app')
@section('title', $tenant->full_name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Khách thuê</a></li>
    <li class="breadcrumb-item active">{{ $tenant->full_name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-800 mx-auto mb-3"
                    style="width:80px;height:80px;font-size:2rem">
                    {{ mb_substr($tenant->full_name, 0, 1) }}
                </div>
                <h5 class="fw-700">{{ $tenant->full_name }}</h5>
                <div class="text-muted mb-1">{{ $tenant->occupation ?? 'Chưa cập nhật' }}</div>
                <span class="badge bg-light text-dark">{{ $tenant->gender == 'male' ? '♂ Nam' : ($tenant->gender == 'female' ? '♀ Nữ' : 'Khác') }}</span>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">📞 Liên hệ</div>
            <div class="card-body">
                <div class="mb-2"><strong>SĐT:</strong> <a href="tel:{{ $tenant->phone }}">{{ $tenant->phone }}</a></div>
                <div class="mb-2"><strong>Email:</strong> {{ $tenant->email ?? '—' }}</div>
                <div class="mb-2"><strong>CCCD:</strong> <code>{{ $tenant->id_card }}</code></div>
                <div class="mb-2"><strong>Ngày sinh:</strong> {{ $tenant->date_of_birth ? \Carbon\Carbon::parse($tenant->date_of_birth)->format('d/m/Y') : '—' }}</div>
                <div><strong>Địa chỉ:</strong> {{ $tenant->address ?? '—' }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">🆘 Liên hệ khẩn cấp</div>
            <div class="card-body">
                <div class="mb-1"><strong>Người thân:</strong> {{ $tenant->emergency_contact ?? '—' }}</div>
                <div><strong>SĐT:</strong> {{ $tenant->emergency_phone ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-700 mb-0">📄 Lịch sử hợp đồng</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Sửa
                </a>
                <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-plus"></i> Hợp đồng mới
                </a>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Phòng / Nhà</th>
                            <th>Thời hạn</th>
                            <th>Tiền thuê</th>
                            <th>Đặt cọc</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $c)
                        <tr>
                            <td>
                                <div class="fw-600" style="font-size:.85rem">{{ $c->property_title }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $c->address }}</div>
                            </td>
                            <td style="font-size:.8rem">
                                {{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }}
                                → {{ \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') }}
                            </td>
                            <td class="fw-600 text-primary">{{ number_format($c->monthly_rent) }}đ</td>
                            <td>{{ number_format($c->deposit) }}đ</td>
                            <td>
                                @if($c->status == 'active')
                                    <span class="badge badge-available">Đang hiệu lực</span>
                                @elseif($c->status == 'expired')
                                    <span class="badge bg-secondary">Hết hạn</span>
                                @else
                                    <span class="badge badge-rented">Đã chấm dứt</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Chưa có hợp đồng nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
