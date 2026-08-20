# Hướng Dẫn Setup Project

## 1. Clone & Cài Đặt Package

Mở Terminal và thực hiện lần lượt các lệnh sau:

```bash
# Clone repository về máy local
git clone <repo-url>
cd <your-folder-repo>

# Cài đặt các thư viện PHP Backend
composer install

# Cài đặt các package cốt lõi cho dự án
composer require laravel/sanctum    # API token authentication
composer require laravel/socialite  # Login qua Facebook / Google / Twitter
composer require guzzlehttp/guzzle # HTTP Client gọi Slack Incoming Webhook (thường có sẵn)

# Cài đặt các gói phụ thuộc Frontend (Build asset Vite)
npm install

```

---

## 2. Khởi Tạo Database Thủ Công

### Cách 1: Qua phpMyAdmin

- Truy cập địa chỉ: `http://localhost/phpmyadmin`
- Tạo mới Database với các thông số:
- **Database name:** `brew_bite`
- **Collation:** `utf8mb4_unicode_ci`

### Cách 2: Qua Terminal / MySQL CLI

```bash
mysql -u root -p

```

```sql
CREATE DATABASE brew_bite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

```

---

## 3. Cấu Hình File `.env`

Sao chép file cấu hình mẫu và khởi tạo App Key bảo mật:

```bash
# Tạo file .env cá nhân từ .env.example
cp .env.example .env

# Sinh APP_KEY cho Laravel
php artisan key:generate

```

Sau khi sinh key, mở file `.env` vừa tạo và điền các giá trị thông số phù hợp với môi trường local của bạn

| Biến Môi Trường                       | Lấy Dữ Liệu Từ Đâu / Hướng Dẫn                                                                                  |
| ------------------------------------- | --------------------------------------------------------------------------------------------------------------- |
| **`DB_USERNAME` / `DB_PASSWORD**`     | Theo cấu hình MySQL local của bạn (Laragon mặc định `root` / `rỗng`)                                            |
| **`FACEBOOK_CLIENT_ID` / `SECRET**`   | Tạo App test tại [developers.facebook.com](https://developers.facebook.com)                                     |
| **`GOOGLE_CLIENT_ID` / `SECRET**`     | Tạo OAuth credential tại [console.cloud.google.com](https://console.cloud.google.com)                           |
| **`TWITTER_CLIENT_ID` / `SECRET**`    | Tạo App tại [developer.twitter.com](https://developer.twitter.com)                                              |
| **`SLACK_ORDER_WEBHOOK_URL`**         | Tạo tại Slack App $\rightarrow$ Incoming Webhooks, dán URL webhook của room chung team                          |
| **`MAIL_USERNAME` / `MAIL_PASSWORD**` | Đăng ký tài khoản free tại [mailtrap.io](https://mailtrap.io) $\rightarrow$ Lấy SMTP credentials của inbox test |

> **Lưu ý Social Provider:** Khai báo chính xác **Redirect URI** trên từng Developer Console tương ứng với giá trị định sẵn trong `.env.example` (Ví dụ: `http://localhost:8000/auth/facebook/callback`).

---

## 4. Chạy Migration

Chạy lệnh tạo bộ khung cơ sở dữ liệu và dữ liệu mẫu:

```bash
php artisan migrate

```

---

## 5. Liên Kết Thư Mục Storage Ảnh Sản Phẩm

Khởi tạo Symbolic Link kết nối thư mục `storage` sang `public` để hiển thị các file ảnh sản phẩm upload trên môi trường local:

```bash
php artisan storage:link

```

> Lệnh trên sẽ tạo symbolic link từ `public/storage` $\rightarrow$ `storage/app/public`, giúp ảnh upload được truy cập công khai qua đường dẫn URL trình duyệt.

---

## 6. Cấu Hình Laravel Scheduler

Các tác vụ định kỳ được định nghĩa trong `routes/console.php` (bao gồm command `report:monthly-orders` được lên lịch chạy lúc 00:00 ngày đầu tiên của mỗi tháng) chỉ được kích hoạt trên môi trường server khi cron daemon gọi Laravel Scheduler mỗi phút:

```bash
* * * * * cd /path/to/nhom1_php && php artisan schedule:run >> /dev/null 2>&1
```
