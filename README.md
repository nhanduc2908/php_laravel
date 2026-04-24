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