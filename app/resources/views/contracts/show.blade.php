@extends('layouts.app')
@section('title', 'Chi tiết hợp đồng #' . $contract->id)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Hợp đồng</a></li>
    <li class="breadcrumb-item active">HĐ #{{ $contract->id }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-700 mb-0">📄 Hợp đồng thuê #{{ $contract->id }}</h5>
    <div class="d-flex gap-2">
        @if($contract->status == 'active')
            <span class="badge badge-available px-3 py-2" style="font-size:.85rem">✅ Đang hiệu lực</span>
        @else
            <span class="badge bg-secondary px-3 py-2" style="font-size:.85rem">⏰ Hết hạn</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">🏠 Thông tin bất động sản</div>
            <div class="card-body">
                <h6 class="fw-700">{{ $contract->property_title }}</h6>
                <div class="mb-2 text-muted"><i class="bi bi-geo-alt"></i> {{ $contract->property_address }}</div>
                <div class="mb-1"><strong>Giá niêm yết:</strong> <span class="text-primary fw-600">{{ number_format($contract->property_price) }}đ/tháng</span></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">👤 Thông tin khách thuê</div>
            <div class="card-body">
                <h6 class="fw-700">{{ $contract->tenant_name }}</h6>
                <div class="mb-1"><strong>CCCD:</strong> <code>{{ $contract->tenant_id_card }}</code></div>
                <div class="mb-1"><strong>SĐT:</strong> <a href="tel:{{ $contract->tenant_phone }}">{{ $contract->tenant_phone }}</a></div>
                <div class="mb-1"><strong>Email:</strong> {{ $contract->tenant_email ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">💰 Chi tiết tài chính & Thời hạn</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">📅</div>
                        <div class="fw-700">{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}</div>
                        <div class="text-muted" style="font-size:.75rem">Ngày bắt đầu</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">🏁</div>
                        <div class="fw-700">{{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</div>
                        <div class="text-muted" style="font-size:.75rem">Ngày kết thúc</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">💵</div>
                        <div class="fw-700 text-primary">{{ number_format($contract->monthly_rent) }}đ</div>
                        <div class="text-muted" style="font-size:.75rem">Tiền thuê/tháng</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">🔐</div>
                        <div class="fw-700 text-success">{{ number_format($contract->deposit) }}đ</div>
                        <div class="text-muted" style="font-size:.75rem">Tiền đặt cọc</div>
                    </div>
                </div>
                @if($contract->notes)
                <div class="mt-3 pt-3 border-top">
                    <strong>📝 Ghi chú:</strong>
                    <p class="text-muted mb-0 mt-1">{{ $contract->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>
@endsection
