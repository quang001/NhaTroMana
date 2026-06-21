# 🏠 NhaTroMana - Hệ thống Quản lý Nhà Trọ

Ứng dụng quản lý bất động sản / nhà trọ được xây dựng bằng **PHP Laravel**, đóng gói bằng **Docker Compose** với 4 containers riêng biệt.

---

## 🏗️ Kiến trúc Docker (4 Containers)

```
┌────────────────────────────────────────────┐
│              Docker Network                │
│                                            │
│  ┌──────────┐   ┌──────────┐               │
│  │  nginx   │──▶│   php    │              │
│  │ :80      │   │ (fpm)    │               │
│  └──────────┘   └────┬─────┘               │
│                      │                     │
│                 ┌────▼─────┐               │
│                 │  mysql   │               │
│                 │  :3306   │               │
│                 └──────────┘               │
│                                            │
│  ┌──────────┐                              │
│  │phpmyadmin│ :8080                        │
│  └──────────┘                              │
└────────────────────────────────────────────┘
```
┌────────────────────────────────────────────┐
│              Docker Network                │
│                                            │
│  ┌──────────┐   ┌──────────┐               │
│  │  nginx   │──▶│   php    │              │
│  │ :80      │   │ (fpm)    │               │
│  └──────────┘   └────┬─────┘               │
│                      │                     │
│                 ┌────▼─────┐               │
│                 │  mysql   │               │
│                 │  :3306   │               │
│                 └──────────┘               │
│                                            │
│  ┌──────────┐                              │
│  │phpmyadmin│ :8080                        │
│  └──────────┘                              │
└────────────────────────────────────────────┘

| Container | Image | Cổng | Chức năng |
|-----------|-------|------|-----------|
| `nhatromana_nginx` | nginx:alpine | 80 | Web server, reverse proxy |
| `nhatromana_php` | php:8.2-fpm (custom) | 9000 | Laravel application |
| `nhatromana_mysql` | mysql:8.0 | 3306 | Cơ sở dữ liệu |
| `nhatromana_phpmyadmin` | phpmyadmin | 8080 | Quản lý database |

---

## 🚀 Cách Chạy

### Yêu cầu
- Docker Desktop (Windows/Mac) hoặc Docker Engine (Linux)
- Docker Compose v2+

### Bước 1: Clone / giải nén project
```bash
cd nhatromana
```

### Bước 2: Chạy script khởi động
```bash
# Linux / Mac
chmod +x start.sh
./start.sh

# Windows (Git Bash)
bash start.sh
```

### Hoặc chạy thủ công
```bash
# Copy .env
cp app/.env.example app/.env

# Tạo thư mục cần thiết
mkdir -p app/storage/{app/public,framework/{cache,sessions,views},logs}
mkdir -p app/bootstrap/cache
chmod -R 777 app/storage app/bootstrap/cache

# Khởi động containers
docker compose up -d --build

# Sinh app key
docker exec nhatromana_php php artisan key:generate

# Clear cache
docker exec nhatromana_php php artisan config:cache
```

### Bước 3: Mở trình duyệt
- 🌐 **Ứng dụng chính**: http://localhost
- 🗄️ **phpMyAdmin**: http://localhost:8080

---

## 📱 Tính năng

### Quản lý Bất động sản
- ✅ Xem danh sách với lọc/tìm kiếm
- ✅ Thêm tin đăng mới (loại nhà, địa chỉ, giá, diện tích, tiện nghi)
- ✅ Xem chi tiết với thông tin đầy đủ
- ✅ Chỉnh sửa / Xóa tin đăng
- ✅ Đánh dấu nổi bật
- ✅ Đếm lượt xem

### Quản lý Khách thuê
- ✅ Danh sách khách thuê + tìm kiếm
- ✅ Thêm/sửa thông tin cá nhân (CCCD, SĐT, nghề nghiệp)
- ✅ Lịch sử hợp đồng của từng khách

### Quản lý Hợp đồng
- ✅ Tạo hợp đồng thuê mới
- ✅ Danh sách hợp đồng với trạng thái
- ✅ Xem chi tiết hợp đồng

### Dashboard
- ✅ Thống kê tổng quan (tổng phòng, trống, đã thuê, doanh thu)
- ✅ Bất động sản nổi bật
- ✅ Tin mới nhất

---

## 🗄️ Cấu trúc Database

```
categories    → Loại BĐS (phòng trọ, căn hộ, nhà trọ...)
locations     → Tỉnh/thành, quận/huyện  
properties    → Bất động sản / phòng / nhà trọ
tenants       → Khách thuê
contracts     → Hợp đồng thuê
invoices      → Hóa đơn hàng tháng
```

---

## 🛠️ Lệnh Docker hữu ích

```bash
# Xem trạng thái containers
docker compose ps

# Xem logs realtime
docker compose logs -f

# Xem logs của 1 container cụ thể
docker compose logs -f php
docker compose logs -f nginx
docker compose logs -f mysql

# Vào trong container PHP
docker exec -it nhatromana_php bash

# Chạy lệnh artisan
docker exec nhatromana_php php artisan migrate
docker exec nhatromana_php php artisan db:seed
docker exec nhatromana_php php artisan cache:clear

# Dừng tất cả containers
docker compose down

# Dừng và xóa volumes (reset database)
docker compose down -v

# Rebuild containers
docker compose up -d --build
```

---

## 📂 Cấu trúc Project

```
nhatromana/
├── docker-compose.yml          # Định nghĩa 4 containers
├── start.sh                    # Script khởi động tự động
├── README.md
│
├── docker/
│   └── Dockerfile.php          # Build container PHP-FPM + Laravel
│
├── nginx/
│   └── conf.d/default.conf     # Cấu hình Nginx
│
├── mysql/
│   └── init.sql                # Script khởi tạo DB + dữ liệu mẫu
│
└── app/                        # Mã nguồn Laravel
    ├── app/Http/Controllers/   # Controllers
    ├── resources/views/        # Blade templates (giao diện)
    ├── routes/web.php          # Định tuyến URL
    ├── .env.example            # Cấu hình môi trường
    └── composer.json
```

---

## 👨‍💻 Công nghệ sử dụng

| Công nghệ | Phiên bản | Mô tả |
|-----------|-----------|-------|
| PHP | 8.2 | Ngôn ngữ lập trình |
| Laravel | 11.x | PHP Framework |
| MySQL | 8.0 | Cơ sở dữ liệu |
| Nginx | Alpine | Web Server |
| Docker | Latest | Container hóa |
| Bootstrap | 5.3 | CSS Framework |
| Bootstrap Icons | 1.11 | Icons |

---

*Dự án học tập - Điện toán đám mây với AWS - Laravel + Docker*
