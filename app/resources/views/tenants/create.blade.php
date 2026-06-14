@extends('layouts.app')
@section('title', 'Thêm khách thuê')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Khách thuê</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<h5 class="mb-4 fw-700">👤 Thêm Khách thuê mới</h5>

@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
</div>
@endif

<form method="POST" action="{{ route('tenants.store') }}">
@csrf
<div class="card mb-3">
    <div class="card-header">Thông tin cá nhân</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">CCCD / CMND <span class="text-danger">*</span></label>
                <input type="text" name="id_card" class="form-control @error('id_card') is-invalid @enderror"
                    value="{{ old('id_card') }}" required placeholder="079123456789">
                @error('id_card')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select">
                    <option value="">Chọn...</option>
                    <option value="male" {{ old('gender')=='male'?'selected':'' }}>Nam</option>
                    <option value="female" {{ old('gender')=='female'?'selected':'' }}>Nữ</option>
                    <option value="other" {{ old('gender')=='other'?'selected':'' }}>Khác</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nghề nghiệp</label>
                <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}" placeholder="Sinh viên, Nhân viên...">
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ thường trú</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Địa chỉ quê quán / thường trú">
            </div>
            <div class="col-md-6">
                <label class="form-label">Người liên hệ khẩn cấp</label>
                <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}" placeholder="Tên người thân">
            </div>
            <div class="col-md-6">
                <label class="form-label">SĐT khẩn cấp</label>
                <input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone') }}" placeholder="0901234567">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">Hủy</a>
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-person-plus me-1"></i> Thêm khách thuê
    </button>
</div>
</form>
</div>
</div>
@endsection
