@extends('layouts.app')

@section('title', 'Đăng tin bất động sản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Bất động sản</a></li>
    <li class="breadcrumb-item active">Đăng tin mới</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">
<h5 class="mb-4 fw-700">📝 Đăng tin Bất động sản / Nhà trọ mới</h5>

@if($errors->any())
<div class="alert alert-danger">
    <i class="bi bi-exclamation-circle me-2"></i>
    <strong>Vui lòng kiểm tra lại:</strong>
    <ul class="mb-0 mt-1">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('properties.store') }}">
@csrf
<div class="card mb-3">
    <div class="card-header">🏠 Thông tin cơ bản</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                placeholder="Ví dụ: Phòng trọ sạch đẹp, gần ĐH, giá rẻ quận 3"
                value="{{ old('title') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Loại bất động sản <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">Chọn loại...</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Khu vực</label>
                <select name="location_id" class="form-select">
                    <option value="">Chọn khu vực...</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->district }}, {{ $loc->city }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                placeholder="Số nhà, đường, phường/xã, quận/huyện..."
                value="{{ old('address') }}" required>
            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mt-3">
            <label class="form-label">Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="4"
                placeholder="Mô tả về phòng/nhà: nội thất, ưu điểm, môi trường xung quanh...">{{ old('description') }}</textarea>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">📐 Chi tiết phòng / nhà</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Giá thuê (VNĐ/tháng) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                    placeholder="3500000" value="{{ old('price') }}" required min="100000">
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Diện tích (m²)</label>
                <input type="number" name="area" class="form-control" placeholder="20" value="{{ old('area') }}" step="0.5">
            </div>
            <div class="col-md-3">
                <label class="form-label">Số phòng ngủ</label>
                <select name="bedrooms" class="form-select">
                    @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('bedrooms') == $i ? 'selected' : '' }}>{{ $i }} PN</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Số người tối đa</label>
                <input type="number" name="max_tenants" class="form-control" placeholder="2" value="{{ old('max_tenants', 2) }}" min="1">
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Trạng thái</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="available"
                        {{ old('status', 'available') == 'available' ? 'checked' : '' }} id="s1">
                    <label class="form-check-label" for="s1">✅ Còn trống</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="rented"
                        {{ old('status') == 'rented' ? 'checked' : '' }} id="s2">
                    <label class="form-check-label" for="s2">🔑 Đã thuê</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="maintenance"
                        {{ old('status') == 'maintenance' ? 'checked' : '' }} id="s3">
                    <label class="form-check-label" for="s3">🔧 Bảo trì</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">✨ Tiện nghi & Dịch vụ</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="has_wifi" id="wifi" {{ old('has_wifi') ? 'checked' : '' }}>
                    <label class="form-check-label" for="wifi">📶 Wifi miễn phí</label>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="has_parking" id="parking" {{ old('has_parking') ? 'checked' : '' }}>
                    <label class="form-check-label" for="parking">🅿️ Chỗ đỗ xe</label>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="has_ac" id="ac" {{ old('has_ac') ? 'checked' : '' }}>
                    <label class="form-check-label" for="ac">❄️ Điều hòa</label>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="has_washing" id="washing" {{ old('has_washing') ? 'checked' : '' }}>
                    <label class="form-check-label" for="washing">🌀 Máy giặt</label>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="has_kitchen" id="kitchen" {{ old('has_kitchen') ? 'checked' : '' }}>
                    <label class="form-check-label" for="kitchen">🍳 Nhà bếp</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">👤 Thông tin liên hệ chủ nhà</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Họ tên chủ nhà <span class="text-danger">*</span></label>
                <input type="text" name="owner_name" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('owner_name') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="owner_phone" class="form-control" placeholder="0901234567" value="{{ old('owner_phone') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="owner_email" class="form-control" placeholder="owner@email.com" value="{{ old('owner_email') }}">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-x-lg"></i> Hủy
    </a>
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg"></i> Đăng tin ngay
    </button>
</div>
</form>
</div>
</div>
@endsection
