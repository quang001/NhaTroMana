@extends('layouts.app')
@section('title', 'Tạo hợp đồng thuê')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Hợp đồng</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<h5 class="mb-4 fw-700">📝 Tạo Hợp đồng thuê mới</h5>

@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
</div>
@endif

<form method="POST" action="{{ route('contracts.store') }}">
@csrf
<div class="card mb-3">
    <div class="card-header">🏠 Thông tin hợp đồng</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bất động sản <span class="text-danger">*</span></label>
                <select name="property_id" class="form-select" required>
                    <option value="">Chọn phòng/nhà còn trống...</option>
                    @foreach($properties as $p)
                    <option value="{{ $p->id }}" {{ old('property_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->title }} — {{ number_format($p->price) }}đ/tháng
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Khách thuê <span class="text-danger">*</span></label>
                <select name="tenant_id" class="form-select" required>
                    <option value="">Chọn khách thuê...</option>
                    @foreach($tenants as $t)
                    <option value="{{ $t->id }}" {{ old('tenant_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->full_name }} — {{ $t->phone }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d', strtotime('+12 months'))) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tiền thuê/tháng (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="monthly_rent" class="form-control" value="{{ old('monthly_rent') }}" required min="0" placeholder="3500000">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tiền đặt cọc (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="deposit" class="form-control" value="{{ old('deposit') }}" required min="0" placeholder="7000000">
            </div>
            <div class="col-12">
                <label class="form-label">Ghi chú</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Điều khoản bổ sung, ghi chú đặc biệt...">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">Hủy</a>
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg me-1"></i> Tạo hợp đồng
    </button>
</div>
</form>
</div>
</div>
@endsection
