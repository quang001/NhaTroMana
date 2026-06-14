#!/bin/bash

# ============================================================
# Script khởi động NhaTroMana - Laravel + Docker
# Tác giả: Cloud Computing Lab
# ============================================================

set -e

echo "🏠 ====================================="
echo "   NhaTroMana - Khởi động hệ thống"
echo "======================================="

# Màu sắc terminal
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

check_docker() {
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}❌ Docker chưa được cài đặt!${NC}"
        echo "   Tải tại: https://docs.docker.com/get-docker/"
        exit 1
    fi
    if ! command -v docker compose &> /dev/null; then
        echo -e "${RED}❌ Docker Compose chưa được cài đặt!${NC}"
        exit 1
    fi
    echo -e "${GREEN}✅ Docker & Docker Compose đã sẵn sàng${NC}"
}

setup_env() {
    if [ ! -f "./app/.env" ]; then
        echo -e "${YELLOW}📋 Tạo file .env từ .env.example...${NC}"
        cp ./app/.env.example ./app/.env
        echo -e "${GREEN}✅ File .env đã được tạo${NC}"
    else
        echo -e "${GREEN}✅ File .env đã tồn tại${NC}"
    fi
}

create_directories() {
    echo -e "${YELLOW}📁 Tạo các thư mục cần thiết...${NC}"
    mkdir -p ./app/storage/app/public
    mkdir -p ./app/storage/framework/{cache,sessions,views,testing}
    mkdir -p ./app/storage/logs
    mkdir -p ./app/bootstrap/cache
    chmod -R 777 ./app/storage
    chmod -R 777 ./app/bootstrap/cache
    echo -e "${GREEN}✅ Thư mục đã sẵn sàng${NC}"
}

start_containers() {
    echo -e "${YELLOW}🐳 Khởi động các Docker containers...${NC}"
    echo "   • nginx  → Web Server (port 80)"
    echo "   • php    → Laravel App (PHP-FPM)"
    echo "   • mysql  → Database MySQL (port 3306)"
    echo "   • phpmyadmin → Quản lý DB (port 8080)"
    echo ""

    docker compose up -d --build

    echo -e "${GREEN}✅ Tất cả containers đã khởi động!${NC}"
}

wait_for_mysql() {
    echo -e "${YELLOW}⏳ Đợi MySQL sẵn sàng...${NC}"
    attempt=0
    max_attempts=30
    while [ $attempt -lt $max_attempts ]; do
        if docker exec nhatromana_mysql mysqladmin ping -h localhost -u root -proot_password --silent 2>/dev/null; then
            echo -e "${GREEN}✅ MySQL đã sẵn sàng!${NC}"
            return 0
        fi
        attempt=$((attempt + 1))
        echo -n "."
        sleep 2
    done
    echo -e "${RED}❌ MySQL không phản hồi sau ${max_attempts} lần thử${NC}"
    return 1
}

setup_laravel() {
    echo -e "${YELLOW}⚙️  Cấu hình Laravel...${NC}"

    # Generate App Key
    docker exec nhatromana_php php artisan key:generate --force 2>/dev/null || true
    echo "   ✅ APP_KEY đã được tạo"

    # Clear cache
    docker exec nhatromana_php php artisan config:clear 2>/dev/null || true
    docker exec nhatromana_php php artisan cache:clear 2>/dev/null || true
    echo "   ✅ Cache đã được xóa"

    # Optimize
    docker exec nhatromana_php php artisan config:cache 2>/dev/null || true
    docker exec nhatromana_php php artisan route:cache 2>/dev/null || true
    echo "   ✅ Laravel đã được optimize"
}

show_info() {
    echo ""
    echo "======================================="
    echo -e "${GREEN}🎉 Hệ thống đã khởi động thành công!${NC}"
    echo "======================================="
    echo ""
    echo "📌 Truy cập ứng dụng:"
    echo -e "   🌐 Web App:     ${GREEN}http://localhost${NC}"
    echo -e "   🗄️  phpMyAdmin: ${GREEN}http://localhost:8080${NC}"
    echo ""
    echo "📌 Thông tin Docker containers:"
    docker compose ps
    echo ""
    echo "📌 Lệnh hữu ích:"
    echo "   docker compose logs -f        # Xem logs"
    echo "   docker compose down           # Dừng hệ thống"
    echo "   docker compose restart        # Restart"
    echo "   docker exec -it nhatromana_php bash  # Vào container PHP"
    echo ""
}

# === Chạy các bước ===
check_docker
setup_env
create_directories
start_containers
wait_for_mysql
sleep 3
setup_laravel
show_info
