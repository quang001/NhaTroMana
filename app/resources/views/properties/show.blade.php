@extends('layouts.app')
@section('title', $property->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Bất động sản</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="fw-700 mb-1">{{ $property->title }}</h4>
                        <div class="text-muted"><i class="bi bi-geo-alt"></i> {{ $property->address }}</div>
                    </div>
                    <div class="text-end">
                        <div class="price-tag fs-4">{{ number_format($property->price) }}đ</div>
                        <small class="text-muted">/tháng</small>
                    </div>
                </div>

                <!-- Ảnh placeholder -->
                <div class="rounded-3 mb-3 d-flex align-items-center justify-content-center"
                    style="height:250px; background:linear-gradient(135deg,#dbeafe,#ede9fe);">
                    <span style="font-size:6rem">{{ $property->category_icon ?? '🏠' }}</span>
                </div>

                <!-- Trạng thái -->
                <div class="d-flex gap-2 mb-3">
                    <span class="badge {{ $property->status == 'available' ? 'badge-available' : 'badge-rented' }} px-3 py-2" style="font-size:.8rem">
                        @if($property->status == 'available') ✅ Còn trống
                        @elseif($property->status == 'rented') 🔑 Đã thuê
                        @else 🔧 Bảo trì @endif
                    </span>
                    @if($property->is_featured)
                    <span class="badge bg-warning text-dark px-3 py-2" style="font-size:.8rem">⭐ Nổi bật</span>
                    @endif
                    <span class="badge bg-light text-dark px-3 py-2" style="font-size:.8rem">
                        👁 {{ $property->views }} lượt xem
                    </span>
                </div>

                <!-- Thông số -->
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">📐</div>
                        <div class="fw-600">{{ $property->area }}m²</div>
                        <div class="text-muted" style="font-size:.75rem">Diện tích</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">🛏</div>
                        <div class="fw-600">{{ $property->bedrooms }} phòng</div>
                        <div class="text-muted" style="font-size:.75rem">Phòng ngủ</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">🚿</div>
                        <div class="fw-600">{{ $property->bathrooms }} phòng</div>
                        <div class="text-muted" style="font-size:.75rem">Phòng tắm</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div style="font-size:1.5rem">👥</div>
                        <div class="fw-600">{{ $property->max_tenants }} người</div>
                        <div class="text-muted" style="font-size:.75rem">Tối đa</div>
                    </div>
                </div>

                <!-- Tiện nghi -->
                <div class="mb-3">
                    <h6 class="fw-700 mb-2">✨ Tiện nghi</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($property->has_wifi)<span class="amenity-tag py-2 px-3">📶 Wifi</span>@endif
                        @if($property->has_parking)<span class="amenity-tag py-2 px-3">🅿️ Đỗ xe</span>@endif
                        @if($property->has_ac)<span class="amenity-tag py-2 px-3">❄️ Điều hòa</span>@endif
                        @if($property->has_washing)<span class="amenity-tag py-2 px-3">🌀 Máy giặt</span>@endif
                        @if($property->has_kitchen)<span class="amenity-tag py-2 px-3">🍳 Bếp</span>@endif
                    </div>
                </div>

                @if($property->description)
                <div>
                    <h6 class="fw-700 mb-2">📋 Mô tả chi tiết</h6>
                    <p class="text-muted" style="font-size:.9rem; line-height:1.7">{{ $property->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Thông tin liên hệ -->
        <div class="card mb-3">
            <div class="card-header">👤 Thông tin liên hệ</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-700"
                        style="width:48px;height:48px;font-size:1.2rem">
                        {{ mb_substr($property->owner_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-600">{{ $property->owner_name }}</div>
                        <div class="text-muted" style="font-size:.8rem">Chủ nhà</div>
                    </div>
                </div>
                <a href="tel:{{ $property->owner_phone }}" class="btn btn-success w-100 mb-2">
                    📞 {{ $property->owner_phone }}
                </a>
                @if($property->owner_email)
                <a href="mailto:{{ $property->owner_email }}" class="btn btn-outline-primary w-100">
                    ✉️ {{ $property->owner_email }}
                </a>
                @endif
            </div>
        </div>

        <!-- Thao tác -->
        <div class="card mb-3">
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Chỉnh sửa tin
                </a>
                @if($property->status == 'available')
                <a href="{{ route('contracts.create') }}" class="btn btn-success">
                    <i class="bi bi-file-text me-1"></i> Tạo hợp đồng thuê
                </a>
                @endif
                <form method="POST" action="{{ route('properties.destroy', $property->id) }}"
                    onsubmit="return confirm('Xác nhận xóa tin này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i> Xóa tin
                    </button>
                </form>
            </div>
        </div>

        <!-- Thông tin vị trí -->
        <div class="card">
            <div class="card-header">📍 Vị trí</div>
            <div class="card-body">
                <div class="mb-1"><strong>Địa chỉ:</strong> {{ $property->address }}</div>
                @if($property->district)
                <div class="mb-1"><strong>Quận/Huyện:</strong> {{ $property->district }}</div>
                @endif
                @if($property->city)
                <div><strong>Tỉnh/TP:</strong> {{ $property->city }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tin tương tự -->
@if($similar->count() > 0)
<div class="mt-4">
    <h5 class="fw-700 mb-3">🏠 Bất động sản tương tự</h5>
    <div class="row g-3">
        @foreach($similar as $s)
        <div class="col-md-4">
            <div class="property-card">
                <div class="prop-img"><span>{{ $s->category_icon }}</span></div>
                <div class="p-3">
                    <div class="fw-600 mb-1" style="font-size:.875rem">{{ Str::limit($s->title, 45) }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price-tag">{{ number_format($s->price) }}đ/tháng</span>
                        <a href="{{ route('properties.show', $s->id) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
