@extends('layouts.app')

@section('title', 'Danh sách Bất động sản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Bất động sản</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-700">🏠 Danh sách Bất động sản / Nhà trọ</h5>
        <small class="text-muted">Tổng: {{ $stats['total'] }} | Còn trống: {{ $stats['available'] }} | Đã thuê: {{ $stats['rented'] }}</small>
    </div>
    <a href="{{ route('properties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Đăng tin
    </a>
</div>

<!-- Bộ lọc tìm kiếm -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('properties.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <div class="search-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" name="keyword" class="form-control"
                            placeholder="Tìm kiếm theo tên, địa chỉ..."
                            value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Tất cả loại</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>✅ Còn trống</option>
                        <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>🔑 Đã thuê</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>🔧 Bảo trì</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="max_price" class="form-control"
                        placeholder="Giá tối đa (đ)" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                    <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Grid bất động sản -->
@if($properties->count() > 0)
<div class="row g-3">
    @foreach($properties as $p)
    <div class="col-md-6 col-xl-4">
        <div class="property-card h-100">
            <div class="prop-img">
                <span style="font-size:3.5rem">{{ $p->category_icon ?? '🏠' }}</span>
                @if($p->is_featured)
                <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark" style="font-size:.65rem">
                    ⭐ Nổi bật
                </span>
                @endif
                <span class="prop-badge {{ $p->status == 'available' ? 'badge-available' : ($p->status == 'rented' ? 'badge-rented' : 'badge-maintenance') }}">
                    @if($p->status == 'available') ✅ Còn trống
                    @elseif($p->status == 'rented') 🔑 Đã thuê
                    @else 🔧 Bảo trì @endif
                </span>
            </div>
            <div class="p-3">
                <div class="fw-600 mb-1" style="font-size:.9rem; line-height:1.4">{{ $p->title }}</div>
                <div class="text-muted mb-2" style="font-size:.78rem">
                    <i class="bi bi-geo-alt"></i>
                    {{ Str::limit($p->address, 50) }}
                    @if($p->district) • {{ $p->district }} @endif
                </div>

                <!-- Thông tin nhanh -->
                <div class="d-flex gap-2 mb-2 flex-wrap">
                    @if($p->area)<span class="amenity-tag">📐 {{ $p->area }}m²</span>@endif
                    @if($p->bedrooms)<span class="amenity-tag">🛏 {{ $p->bedrooms }} PN</span>@endif
                    @if($p->max_tenants)<span class="amenity-tag">👤 {{ $p->max_tenants }} người</span>@endif
                </div>

                <!-- Tiện ích -->
                <div class="d-flex gap-1 mb-3 flex-wrap">
                    @if($p->has_wifi)<span class="amenity-tag">📶 Wifi</span>@endif
                    @if($p->has_parking)<span class="amenity-tag">🅿️ Đỗ xe</span>@endif
                    @if($p->has_ac)<span class="amenity-tag">❄️ Điều hòa</span>@endif
                    @if($p->has_kitchen)<span class="amenity-tag">🍳 Bếp</span>@endif
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <div>
                        <div class="price-tag">{{ number_format($p->price) }}đ</div>
                        <div class="text-muted" style="font-size:.7rem">/tháng</div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('properties.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('properties.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('properties.destroy', $p->id) }}" class="d-inline"
                            onsubmit="return confirm('Xác nhận xóa?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $properties->withQueryString()->links() }}
</div>

@else
<div class="card">
    <div class="card-body text-center py-5">
        <div style="font-size:4rem">🔍</div>
        <h5 class="mt-3">Không tìm thấy bất động sản nào</h5>
        <p class="text-muted">Thử thay đổi bộ lọc hoặc đăng tin mới</p>
        <a href="{{ route('properties.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Đăng tin ngay
        </a>
    </div>
</div>
@endif
@endsection
