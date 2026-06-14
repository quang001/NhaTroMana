@extends('layouts.app')
@section('title', 'Chỉnh sửa khách thuê')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Khách thuê</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<h5 class="mb-4 fw-700">✏️ Chỉnh sửa thông tin: {{ $tenant->full_name }}</h5>

<form method="POST" action="{{ route('tenants.update', $tenant->id) }}">
@csrf @method('PUT')
<div class="card mb-3">
    <div class="card-header">Thông tin cá nhân</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="{{ $tenant->full_name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ $tenant->phone }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $tenant->email }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nghề nghiệp</label>
                <input type="text" name="occupation" class="form-control" value="{{ $tenant->occupation }}">
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ thường trú</label>
                <input type="text" name="address" class="form-control" value="{{ $tenant->address }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Người liên hệ khẩn cấp</label>
                <input type="text" name="emergency_contact" class="form-control" value="{{ $tenant->emergency_contact }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">SĐT khẩn cấp</label>
                <input type="text" name="emergency_phone" class="form-control" value="{{ $tenant->emergency_phone }}">
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('tenants.show', $tenant->id) }}" class="btn btn-outline-secondary">Hủy</a>
    <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
</div>
</form>
</div>
</div>
@endsection
