@extends('layouts.app')
@section('title', 'Chỉnh sửa bất động sản')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Bất động sản</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">
<h5 class="mb-4 fw-700">✏️ Chỉnh sửa tin đăng</h5>

@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
</div>
@endif

<form method="POST" action="{{ route('properties.update', $property->id) }}">
@csrf @method('PUT')
<div class="card mb-3">
    <div class="card-header">🏠 Thông tin cơ bản</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ $property->title }}" required>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Loại bất động sản</label>
                <select name="category_id" class="form-select">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $property->category_id == $cat->id ? 'selected' : '' }}>
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
                    <option value="{{ $loc->id }}" {{ $property->location_id == $loc->id ? 'selected' : '' }}>
                        {{ $loc->district }}, {{ $loc->city }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control" value="{{ $property->address }}" required>
        </div>
        <div class="mt-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3">{{ $property->description }}</textarea>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">📐 Chi tiết</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Giá thuê (đ/tháng)</label>
                <input type="number" name="price" class="form-control" value="{{ $property->price }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Diện tích (m²)</label>
                <input type="number" name="area" class="form-control" value="{{ $property->area }}" step="0.5">
            </div>
            <div class="col-md-3">
                <label class="form-label">Phòng ngủ</label>
                <input type="number" name="bedrooms" class="form-control" value="{{ $property->bedrooms }}" min="1">
            </div>
            <div class="col-md-3">
                <label class="form-label">Số người tối đa</label>
                <input type="number" name="max_tenants" class="form-control" value="{{ $property->max_tenants }}" min="1">
            </div>
        </div>
        <div class="mt-3">
            <label class="form-label">Trạng thái</label>
            <div class="d-flex gap-3">
                @foreach(['available' => '✅ Còn trống', 'rented' => '🔑 Đã thuê', 'maintenance' => '🔧 Bảo trì'] as $val => $label)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="{{ $val }}"
                        {{ $property->status == $val ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">✨ Tiện nghi</div>
    <div class="card-body">
        <div class="row g-3">
            @foreach(['has_wifi' => '📶 Wifi', 'has_parking' => '🅿️ Đỗ xe', 'has_ac' => '❄️ Điều hòa', 'has_washing' => '🌀 Máy giặt', 'has_kitchen' => '🍳 Bếp'] as $field => $label)
            <div class="col-6 col-md-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="{{ $field }}" {{ $property->$field ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $label }}</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">👤 Thông tin chủ nhà</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Họ tên</label>
                <input type="text" name="owner_name" class="form-control" value="{{ $property->owner_name }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Điện thoại</label>
                <input type="text" name="owner_phone" class="form-control" value="{{ $property->owner_phone }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="owner_email" class="form-control" value="{{ $property->owner_email }}">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('properties.show', $property->id) }}" class="btn btn-outline-secondary">Hủy</a>
    <button type="submit" class="btn btn-primary px-4">💾 Lưu thay đổi</button>
</div>
</form>
</div>
</div>
@endsection
