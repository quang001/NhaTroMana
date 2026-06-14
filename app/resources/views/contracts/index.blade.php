@extends('layouts.app')
@section('title', 'Hợp đồng thuê')
@section('breadcrumb')
    <li class="breadcrumb-item active">Hợp đồng</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-700">📄 Quản lý Hợp đồng thuê</h5>
    <a href="{{ route('contracts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tạo hợp đồng
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bất động sản</th>
                    <th>Khách thuê</th>
                    <th>Thời hạn</th>
                    <th>Tiền thuê/tháng</th>
                    <th>Đặt cọc</th>
                    <th>Trạng thái</th>
                    <th>Xem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contracts as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>
                        <div class="fw-600" style="font-size:.85rem">{{ Str::limit($c->property_title, 35) }}</div>
                        <div class="text-muted" style="font-size:.75rem"><i class="bi bi-geo-alt"></i> {{ Str::limit($c->property_address, 30) }}</div>
                    </td>
                    <td>
                        <div class="fw-500">{{ $c->tenant_name }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $c->tenant_phone }}</div>
                    </td>
                    <td style="font-size:.8rem">
                        <div>{{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }}</div>
                        <div class="text-muted">→ {{ \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') }}</div>
                    </td>
                    <td class="fw-700 text-primary">{{ number_format($c->monthly_rent) }}đ</td>
                    <td>{{ number_format($c->deposit) }}đ</td>
                    <td>
                        @if($c->status == 'active')
                            <span class="badge badge-available">✅ Hiệu lực</span>
                        @elseif($c->status == 'expired')
                            <span class="badge bg-secondary">⏰ Hết hạn</span>
                        @else
                            <span class="badge badge-rented">❌ Chấm dứt</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('contracts.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có hợp đồng nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $contracts->links() }}</div>
@endsection
