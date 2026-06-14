@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-800">📊 Tổng quan hệ thống</h4>
        <p class="text-muted mb-0" style="font-size:.85rem">Cập nhật: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <a href="{{ route('properties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Đăng tin mới
    </a>
</div>

<!-- Thống kê nhanh -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-blue">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Tổng bất động sản</div>
                    <div class="stat-value">{{ $stats['total_properties'] }}</div>
                </div>
                <div class="stat-icon">🏠</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-green">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Phòng còn trống</div>
                    <div class="stat-value">{{ $stats['available'] }}</div>
                </div>
                <div class="stat-icon">✅</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-orange">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Đang cho thuê</div>
                    <div class="stat-value">{{ $stats['rented'] }}</div>
                </div>
                <div class="stat-icon">🔑</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-purple">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Doanh thu/tháng</div>
                    <div class="stat-value" style="font-size:1.3rem">{{ number_format($stats['total_revenue']) }}đ</div>
                </div>
                <div class="stat-icon">💰</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Tin nổi bật -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>🌟 Bất động sản nổi bật</span>
                <a href="{{ route('properties.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($featured_properties as $p)
                    <div class="col-md-6">
                        <div class="property-card">
                            <div class="prop-img">
                                <span>{{ $p->category_icon ?? '🏠' }}</span>
                                <span class="prop-badge {{ $p->status == 'available' ? 'badge-available' : 'badge-rented' }}">
                                    {{ $p->status == 'available' ? '✅ Còn trống' : '🔑 Đã thuê' }}
                                </span>
                            </div>
                            <div class="p-3">
                                <div class="fw-600 mb-1" style="font-size:.875rem">{{ Str::limit($p->title, 45) }}</div>
                                <div class="text-muted mb-2" style="font-size:.75rem">
                                    <i class="bi bi-geo-alt"></i> {{ $p->district }}, {{ $p->city }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price-tag">{{ number_format($p->price) }}đ/tháng</span>
                                    <a href="{{ route('properties.show', $p->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-4 text-muted">Chưa có tin nổi bật</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar stats -->
    <div class="col-lg-4">
        <!-- Thống kê theo loại -->
        <div class="card mb-3">
            <div class="card-header">📂 Phân loại bất động sản</div>
            <div class="card-body p-0">
                @foreach($category_stats as $cat)
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                    <span style="font-size:.875rem">{{ $cat->icon }} {{ $cat->name }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $cat->count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tin đăng mới nhất -->
        <div class="card">
            <div class="card-header">🕒 Tin mới nhất</div>
            <div class="card-body p-0">
                @foreach($recent_properties as $p)
                <a href="{{ route('properties.show', $p->id) }}" class="d-block text-decoration-none px-3 py-2 border-bottom hover-bg">
                    <div class="fw-500" style="font-size:.8rem; color:#1e293b">{{ Str::limit($p->title, 40) }}</div>
                    <div class="text-muted" style="font-size:.75rem">
                        {{ $p->category_icon }} {{ $p->category_name }} •
                        <span class="price-tag" style="font-size:.75rem">{{ number_format($p->price) }}đ</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
