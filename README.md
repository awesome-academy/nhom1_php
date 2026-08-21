# Brew & Bite

Mock project NAITEI 26 - PHP. Nhóm 1.

Ứng dụng web đặt món ăn & đồ uống, bao gồm:
- **User site**: REST API (xác thực qua Laravel Sanctum, social login)
- **Admin site**: Server-side rendering với Blade

**Repository:** https://github.com/awesome-academy/nhom1_php  
**Redmine:** https://edu-redmine.sun-asterisk.vn/projects/nhom1_php

## Thành viên

- Trần Hải Đông
- Trần Duy Hưng
- Nguyễn Đại Việt Hoàng
- Nguyễn Xuân Thanh B

---

## Công nghệ sử dụng

| Thành phần | Phiên bản |
|---|---|
| PHP | ^8.2 |
| Laravel | ^12.0 |
| Laravel Sanctum | ^4.3 (API token auth) |
| Laravel Socialite | ^5.29 (social login) |
| Database | MySQL |
| Frontend build | Vite + Tailwind CSS |
| Test | PHPUnit ^11 (SQLite in-memory) |

---

## Yêu cầu môi trường

- PHP >= 8.2 (với các extension: `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` hoặc `imagick`)
- Composer >= 2
- Node.js >= 18 + npm
- MySQL >= 8.0

---

## Cài đặt từ đầu

### 1. Clone project

```bash
git clone https://github.com/awesome-academy/nhom1_php.git
cd nhom1_php
```

### 2. Cài PHP dependencies

```bash
composer install
```

### 3. Cài Node dependencies và build assets

```bash
npm install
npm run build
```

### 4. Tạo file `.env`

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Tạo database

#### Qua MySQL CLI

```bash
mysql -u root -p
```

```sql
CREATE DATABASE brew_bite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### Qua phpMyAdmin

Tạo database tên `brew_bite`, collation `utf8mb4_unicode_ci`.

### 6. Cấu hình `.env`

Mở file `.env` và điền các giá trị phù hợp với môi trường local:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brew_bite
DB_USERNAME=root
DB_PASSWORD=          # Laragon mặc định để trống
```

Các biến tùy chọn (social login, mail, Slack) — xem bảng bên dưới.

| Biến | Mô tả | Bắt buộc |
|---|---|---|
| `DB_USERNAME` / `DB_PASSWORD` | Credentials MySQL local | ✅ |
| `MAIL_USERNAME` / `MAIL_PASSWORD` | SMTP Mailtrap — lấy tại [mailtrap.io](https://mailtrap.io) | Nếu cần gửi mail |
| `ADMIN_NOTIFICATION_EMAIL` | Email nhận thông báo order mới | Nếu cần mail admin |
| `FACEBOOK_CLIENT_ID` / `FACEBOOK_CLIENT_SECRET` | Tạo tại [developers.facebook.com](https://developers.facebook.com) | Nếu dùng social login |
| `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` | Tạo tại [console.cloud.google.com](https://console.cloud.google.com) | Nếu dùng social login |
| `TWITTER_CLIENT_ID` / `TWITTER_CLIENT_SECRET` | Tạo tại [developer.twitter.com](https://developer.twitter.com) | Nếu dùng social login |
| `SLACK_ORDER_WEBHOOK_URL` | Tạo Incoming Webhook tại [api.slack.com](https://api.slack.com/apps) | Nếu dùng Slack notification |

> **Lưu ý Redirect URI cho Social Login:** khai báo đúng URL callback trên từng Developer Console, ví dụ `http://localhost:8000/auth/facebook/callback`.

### 7. Chạy migration

```bash
php artisan migrate
```

### 8. Chạy seeder (khuyến nghị)

Tạo tài khoản admin, user mẫu, categories và products:

```bash
php artisan db:seed
```

Sau khi seed, có thể đăng nhập với:

| Tài khoản | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `password` |
| User mẫu | `user1@example.com` | `password_user1` |

### 9. Liên kết storage

```bash
php artisan storage:link
```

### 10. Chạy ứng dụng

```bash
php artisan serve
```

Ứng dụng chạy tại `http://localhost:8000`.

- **User web:** `http://localhost:8000`
- **Admin panel:** `http://localhost:8000/admin/login`

---

## Chạy test

Test dùng SQLite in-memory, không cần cấu hình database riêng.

```bash
php artisan test
```

Chạy một nhóm test cụ thể:

```bash
# Chỉ Feature tests
php artisan test --testsuite=Feature

# Chỉ Unit tests
php artisan test --testsuite=Unit

# Lọc theo tên test class
php artisan test --filter=AdminOrderTest
php artisan test --filter=CancelOrderTest
```

---

## Các lệnh thường dùng

```bash
# Xóa cache khi thay đổi config
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Xem danh sách routes
php artisan route:list

# Reset database + seed lại (cẩn thận: xóa toàn bộ dữ liệu)
php artisan migrate:fresh --seed

# Chạy queue worker (nếu dùng job/notification)
php artisan queue:work

# Build lại frontend assets
npm run build

# Xem log real-time
php artisan pail
```

---

## Cấu trúc API

### Authentication

| Method | Endpoint | Mô tả |
|---|---|---|
| POST | `/api/login` | Đăng nhập, nhận API token |
| POST | `/api/logout` | Đăng xuất (cần Bearer token) |

### User API (cần Bearer token)

| Method | Endpoint | Mô tả |
|---|---|---|
| GET | `/api/cart` | Xem giỏ hàng |
| POST | `/api/cart/items` | Thêm sản phẩm vào giỏ |
| PUT | `/api/cart/items/{id}` | Cập nhật số lượng |
| DELETE | `/api/cart/items/{id}` | Xóa item khỏi giỏ |
| DELETE | `/api/cart` | Xóa toàn bộ giỏ hàng |
| POST | `/api/checkout` | Tạo đơn hàng từ giỏ |
| GET | `/api/orders` | Lịch sử đơn hàng của user |
| GET | `/api/orders/{id}` | Chi tiết đơn hàng |
| PATCH | `/api/orders/{id}/cancel` | Hủy đơn hàng |

### Admin API (cần Bearer token + role admin)

| Method | Endpoint | Mô tả |
|---|---|---|
| GET | `/api/admin/orders` | Danh sách tất cả đơn hàng |
| GET | `/api/admin/orders/{id}` | Chi tiết đơn hàng bất kỳ |
| PATCH | `/api/admin/orders/{id}/status` | Chuyển trạng thái đơn hàng |
| GET | `/api/admin/suggestions` | Danh sách góp ý |
| PUT | `/api/admin/suggestions/{id}` | Cập nhật góp ý |
| GET | `/api/admin/categories` | Danh sách categories |
| POST | `/api/admin/categories` | Tạo category |
| PUT | `/api/admin/categories/{id}` | Cập nhật category |
| DELETE | `/api/admin/categories/{id}` | Xóa category |

### Public API (không cần token)

| Method | Endpoint | Mô tả |
|---|---|---|
| GET | `/api/categories` | Danh sách categories |
| GET | `/api/products` | Danh sách sản phẩm |
| GET | `/api/products/{id}` | Chi tiết sản phẩm |

---

## Quy trình làm việc nhóm

Xem chi tiết tại [REDMINE.md](REDMINE.md).
