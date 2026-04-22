<<<<<<< HEAD
# 🔐 Security Assessment Platform

## Giới thiệu
Nền tảng đánh giá an ninh mạng với 280 tiêu chí, 17 danh mục.

## Yêu cầu
- PHP >= 8.1
- MySQL >= 8.0
- Node.js >= 18
- Docker (khuyến nghị)

## Cài đặt nhanh

### Docker
```bash
cp .env.example .env
docker-compose up -d
docker exec security-php php artisan migrate --seed
=======
# 📄 FILE README.md - HOÀN THIỆN NGẮN GỌN

```markdown
# 🔐 Security Assessment Platform

> Nền tảng đánh giá an ninh mạng với 280 tiêu chí, 17 danh mục

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Docker](https://img.shields.io/badge/docker-ready-2496ED.svg)](https://docker.com)

---

## 📋 Giới thiệu

Hệ thống đánh giá mức độ tuân thủ an ninh cho máy chủ, hỗ trợ quản lý lỗ hổng, tạo báo cáo và phân quyền chi tiết.

### 👥 Vai trò người dùng

| Role | Quyền hạn |
|------|-----------|
| 🔐 Super Admin | Toàn quyền |
| 👑 Admin | Quản lý user, server |
| 🔒 Security Officer | Đánh giá, quét lỗ hổng |
| 👁️ Viewer | Chỉ xem |
| 📊 Auditor | Xem audit logs |

---

## 🚀 Cài đặt nhanh

### Docker (Khuyến nghị)
```bash
cp .env.example .env
docker-compose up -d
```

### Thủ công
```bash
# Backend
cd backend && composer install && php artisan migrate --seed

# Frontend  
cd frontend && npm install && npm run build

# Realtime
cd realtime && npm install && npm start
```

### 🔑 Tài khoản mặc định
| Role | Username | Password |
|------|----------|----------|
| Super Admin | `superadmin` | `Super@123456` |
| Admin | `admin` | `Admin@123456` |
| Security | `security` | `Security@123456` |

Truy cập: `http://localhost:8080`

---

## ✨ Tính năng chính

| Chức năng | Mô tả |
|-----------|-------|
| 🔐 Xác thực | JWT token, 2FA, RBAC |
| 🖥️ Server | CRUD, SSH, scan |
| 📋 Criteria | 280 tiêu chí, import/export |
| 🎯 Assessment | Đánh giá, tính điểm |
| 🐛 Vulnerabilities | Quét CVE, phân loại |
| 📄 Assessment Files | Tạo, sửa, xóa, chia sẻ, version |
| 📊 Reports | PDF/Excel, dashboard |
| ⚡ Realtime | WebSocket, notification |

---

## 📡 API chính

```http
# Auth
POST   /api/v1/auth/login
POST   /api/v1/auth/logout

# Users
GET    /api/v1/users
POST   /api/v1/users
PUT    /api/v1/users/{id}
DELETE /api/v1/users/{id}

# Servers
GET    /api/v1/servers
POST   /api/v1/servers/{id}/scan

# Assessment Files
GET    /api/v1/assessment-files
POST   /api/v1/assessment-files
PUT    /api/v1/assessment-files/{id}
DELETE /api/v1/assessment-files/{id}
POST   /api/v1/assessment-files/{id}/share

# Reports
POST   /api/v1/reports/generate
GET    /api/v1/reports/download/{id}
```

---

## 🧪 Testing

```bash
cd backend
php artisan test              # Chạy all tests
php artisan test --coverage   # Code coverage
make test                     # Hoặc dùng Makefile
```

---

## 📁 Cấu trúc dự án

```
├── backend/          # PHP Core + API
├── frontend/         # 1 file page + modules
├── realtime/         # WebSocket server
├── database/         # SQL scripts
├── docs/             # Tài liệu
├── scripts/          # Utility scripts
└── .docker/          # Docker configs
```

---

## 🔧 Yêu cầu hệ thống

| Thành phần | Yêu cầu |
|------------|---------|
| PHP | 8.1+ |
| MySQL | 8.0+ |
| Node.js | 18+ |
| Redis | 7.0+ |
| Docker | (khuyến nghị) |

---

## 📄 License

MIT © 2024 Security Assessment Platform

---

## 📞 Hỗ trợ

- 📧 Email: support@security-platform.com
- 📖 [Tài liệu chi tiết](docs/)
- 🐛 [Báo lỗi](https://github.com/your-org/security-platform/issues)

```

---

## 📄 FILE .env.example

```env
# Application
APP_NAME="Security Assessment Platform"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080
APP_TIMEZONE=Asia/Ho_Chi_Minh
APP_LOCALE=vi

# Database
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=security_platform
DB_USERNAME=root
DB_PASSWORD=root123

# JWT
JWT_SECRET=your-super-secret-key-change-this
JWT_TTL=3600
JWT_REFRESH_TTL=604800

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null

# Mail
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@security-platform.com
MAIL_FROM_NAME="Security Platform"

# Upload
UPLOAD_MAX_SIZE=10485760
ALLOWED_EXTENSIONS=jpg,jpeg,png,pdf,doc,docx,xls,xlsx

# Rate Limit
RATE_LIMIT_REQUESTS=100
RATE_LIMIT_MINUTES=1

# 2FA
TWO_FACTOR_ENABLED=true

# Backup
BACKUP_PATH=/var/backups
BACKUP_KEEP_DAYS=30

# External APIs
CVE_API_URL=https://services.nvd.nist.gov/rest/json/cves/2.0
CVE_API_KEY=your-nvd-api-key
```

---

## 📄 FILE docker-compose.yml

```yaml
version: '3.8'

services:
  # Nginx Web Server
  nginx:
    image: nginx:alpine
    container_name: security-nginx
    ports:
      - "8080:80"
    volumes:
      - ./backend:/var/www/html
      - ./frontend:/var/www/frontend
      - ./.docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
      - realtime
    networks:
      - security-network

  # PHP-FPM
  php:
    build:
      context: ./.docker
      dockerfile: Dockerfile.php
    container_name: security-php
    volumes:
      - ./backend:/var/www/html
      - ./frontend:/var/www/frontend
    environment:
      - APP_ENV=local
    depends_on:
      - mysql
      - redis
    networks:
      - security-network

  # MySQL Database
  mysql:
    image: mysql:8.0
    container_name: security-mysql
    environment:
      MYSQL_ROOT_PASSWORD: root123
      MYSQL_DATABASE: security_platform
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - security-network

  # Redis Cache
  redis:
    image: redis:alpine
    container_name: security-redis
    ports:
      - "6379:6379"
    networks:
      - security-network

  # Node.js Realtime Server
  realtime:
    build:
      context: ./.docker
      dockerfile: Dockerfile.node
    container_name: security-realtime
    ports:
      - "3000:3000"
    environment:
      - REDIS_HOST=redis
      - DB_HOST=mysql
    depends_on:
      - redis
      - mysql
    networks:
      - security-network

networks:
  security-network:
    driver: bridge

volumes:
  mysql_data:
```

---

## 📄 Makefile

```makefile
.PHONY: help install start stop restart clean test migrate seed backup

help:
	@echo "Available commands:"
	@echo "  make install   - Install all dependencies"
	@echo "  make start     - Start all services"
	@echo "  make stop      - Stop all services"
	@echo "  make restart   - Restart all services"
	@echo "  make test      - Run tests"
	@echo "  make migrate   - Run migrations"
	@echo "  make seed      - Seed database"
	@echo "  make backup    - Backup database"

install:
	@echo "Installing dependencies..."
	cd backend && composer install
	cd frontend && npm install
	cd realtime && npm install

start:
	docker-compose up -d

stop:
	docker-compose down

restart: stop start

test:
	cd backend && php artisan test

migrate:
	docker exec security-php php artisan migrate

seed:
	docker exec security-php php artisan db:seed

backup:
	docker exec security-php php artisan backup:run

logs:
	docker-compose logs -f

shell:
	docker exec -it security-php bash

mysql:
	docker exec -it security-mysql mysql -uroot -proot123 security_platform
```

>>>>>>> 38e4814 (Create README.md)
