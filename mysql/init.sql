-- ============================================
-- Khởi tạo database cho ứng dụng Nhà Trọ
-- ============================================

USE nhatromana;

-- Bảng danh mục loại bất động sản
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Tên loại: Nhà trọ, Căn hộ, Phòng trọ...',
    slug VARCHAR(100) UNIQUE NOT NULL,
    icon VARCHAR(50) DEFAULT '🏠',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng tỉnh/thành phố
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    ward VARCHAR(100),
    full_address TEXT
);

-- Bảng bất động sản / nhà trọ
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Tiêu đề tin đăng',
    slug VARCHAR(255) UNIQUE,
    description TEXT COMMENT 'Mô tả chi tiết',
    category_id INT,
    location_id INT,
    address TEXT NOT NULL COMMENT 'Địa chỉ cụ thể',
    price DECIMAL(15,2) NOT NULL COMMENT 'Giá thuê (VNĐ/tháng)',
    area FLOAT COMMENT 'Diện tích (m²)',
    bedrooms TINYINT DEFAULT 1 COMMENT 'Số phòng ngủ',
    bathrooms TINYINT DEFAULT 1 COMMENT 'Số phòng tắm',
    floor INT COMMENT 'Tầng',
    max_tenants TINYINT DEFAULT 2 COMMENT 'Số người tối đa',
    status ENUM('available','rented','maintenance') DEFAULT 'available' COMMENT 'Trạng thái',
    has_wifi BOOLEAN DEFAULT FALSE,
    has_parking BOOLEAN DEFAULT FALSE,
    has_ac BOOLEAN DEFAULT FALSE,
    has_washing BOOLEAN DEFAULT FALSE,
    has_kitchen BOOLEAN DEFAULT FALSE,
    images JSON COMMENT 'Danh sách ảnh',
    owner_name VARCHAR(100),
    owner_phone VARCHAR(20),
    owner_email VARCHAR(100),
    views INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

-- Bảng khách thuê
CREATE TABLE IF NOT EXISTS tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    id_card VARCHAR(20) UNIQUE COMMENT 'CCCD/CMND',
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT COMMENT 'Địa chỉ thường trú',
    date_of_birth DATE,
    gender ENUM('male','female','other'),
    occupation VARCHAR(100),
    emergency_contact VARCHAR(100),
    emergency_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng hợp đồng thuê
CREATE TABLE IF NOT EXISTS contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    tenant_id INT NOT NULL,
    start_date DATE NOT NULL COMMENT 'Ngày bắt đầu thuê',
    end_date DATE NOT NULL COMMENT 'Ngày kết thúc hợp đồng',
    monthly_rent DECIMAL(15,2) NOT NULL,
    deposit DECIMAL(15,2) COMMENT 'Tiền đặt cọc',
    status ENUM('active','expired','terminated') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
);

-- Bảng hóa đơn
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contract_id INT NOT NULL,
    month_year DATE NOT NULL COMMENT 'Tháng/năm hóa đơn',
    rent_amount DECIMAL(15,2) NOT NULL,
    electricity_amount DECIMAL(15,2) DEFAULT 0,
    water_amount DECIMAL(15,2) DEFAULT 0,
    internet_amount DECIMAL(15,2) DEFAULT 0,
    other_amount DECIMAL(15,2) DEFAULT 0,
    total_amount DECIMAL(15,2) NOT NULL,
    paid_at TIMESTAMP NULL,
    status ENUM('pending','paid','overdue') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(id)
);

-- ============================================
-- Dữ liệu mẫu
-- ============================================

INSERT INTO categories (name, slug, icon) VALUES
('Phòng trọ', 'phong-tro', '🚪'),
('Nhà trọ', 'nha-tro', '🏠'),
('Căn hộ mini', 'can-ho-mini', '🏢'),
('Căn hộ chung cư', 'chung-cu', '🏗️'),
('Nhà nguyên căn', 'nha-nguyen-can', '🏡');

INSERT INTO locations (city, district, ward) VALUES
('TP. Hồ Chí Minh', 'Quận 1', 'Phường Bến Nghé'),
('TP. Hồ Chí Minh', 'Quận 3', 'Phường 6'),
('TP. Hồ Chí Minh', 'Bình Thạnh', 'Phường 13'),
('TP. Hồ Chí Minh', 'Thủ Đức', 'Phường Linh Trung'),
('TP. Hồ Chí Minh', 'Gò Vấp', 'Phường 12'),
('TP. Hồ Chí Minh', 'Tân Bình', 'Phường 7');

INSERT INTO properties (title, slug, description, category_id, location_id, address, price, area, bedrooms, bathrooms, status, has_wifi, has_parking, has_ac, owner_name, owner_phone, is_featured) VALUES
('Phòng trọ sạch đẹp gần ĐH RMIT', 'phong-tro-sach-dep-rmit', 'Phòng trọ mới xây, thoáng mát, an ninh tốt, gần các trường đại học.', 1, 1, '123 Nguyễn Đình Chiểu', 3500000, 20, 1, 1, 'available', TRUE, TRUE, TRUE, 'Nguyễn Văn An', '0901234567', TRUE),
('Căn hộ mini full nội thất Q3', 'can-ho-mini-q3', 'Căn hộ mini đầy đủ nội thất, view đẹp, an ninh 24/7.', 3, 2, '45 Đinh Tiên Hoàng', 6500000, 35, 1, 1, 'available', TRUE, FALSE, TRUE, 'Trần Thị Bình', '0912345678', TRUE),
('Nhà trọ giá rẻ Bình Thạnh', 'nha-tro-binh-thanh', 'Nhà trọ giá rẻ, phù hợp sinh viên, gần chợ và siêu thị.', 2, 3, '78 Xô Viết Nghệ Tĩnh', 2800000, 18, 1, 1, 'available', TRUE, TRUE, FALSE, 'Lê Văn Cường', '0923456789', FALSE),
('Phòng trọ sinh viên gần ĐHQG', 'phong-tro-dhqg', 'Khu nhà trọ dành cho sinh viên, có bảo vệ 24/7, camera an ninh.', 1, 4, '12 Võ Văn Ngân', 2500000, 16, 1, 1, 'rented', TRUE, FALSE, FALSE, 'Phạm Thị Dung', '0934567890', FALSE),
('Căn hộ chung cư cao cấp Gò Vấp', 'can-ho-go-vap', 'Căn hộ 2PN tại chung cư mới, hồ bơi, gym, bảo vệ 24/7.', 4, 5, '99 Nguyễn Kiệm', 12000000, 65, 2, 2, 'available', TRUE, TRUE, TRUE, 'Hoàng Minh Em', '0945678901', TRUE),
('Phòng trọ thoáng mát Tân Bình', 'phong-tro-tan-binh', 'Phòng mới xây, WC riêng, gần sân bay Tân Sơn Nhất.', 1, 6, '34 Cộng Hòa', 4200000, 25, 1, 1, 'available', TRUE, TRUE, TRUE, 'Vũ Thị Hoa', '0956789012', FALSE);

INSERT INTO tenants (full_name, id_card, phone, email, date_of_birth, gender, occupation) VALUES
('Nguyễn Minh Tuấn', '079123456789', '0901111222', 'tuan.nguyen@gmail.com', '2000-05-15', 'male', 'Sinh viên'),
('Trần Thị Mai', '079987654321', '0912222333', 'mai.tran@gmail.com', '1999-08-22', 'female', 'Nhân viên văn phòng'),
('Lê Hoàng Nam', '079555666777', '0923333444', 'nam.le@gmail.com', '2001-03-10', 'male', 'Sinh viên');

INSERT INTO contracts (property_id, tenant_id, start_date, end_date, monthly_rent, deposit, status) VALUES
(4, 1, '2024-01-01', '2024-12-31', 2500000, 5000000, 'active'),
(3, 2, '2024-02-01', '2025-01-31', 2800000, 5600000, 'active');
