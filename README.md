# STT-NF Blog Platform — DevSecOps Lab (Kelompok A)

Aplikasi blog management yang mengandung beberapa kerentanan keamanan untuk keperluan pembelajaran.

## Setup

```bash
# 1. Jalankan SonarQube
docker-compose up -d

# 2. Tunggu SonarQube ready di http://localhost:9000 (±2 menit)

# 3. Install sonar-scanner jika belum ada
# (ikuti instruksi di https://docs.sonarqube.org/latest/analysis/scan/sonarscanner/)

# 4. Scan project
sonar-scanner
```

## Struktur Utama
- `app/Http/Controllers/ArticleController.php` — manajemen artikel
- `app/Http/Controllers/AuthController.php` — autentikasi
- `app/Http/Controllers/FileController.php` — upload/download file
- `app/Http/Controllers/AdminController.php` — panel admin
- `config/database.php` — konfigurasi database
- `app/Http/Middleware/VerifyCsrfToken.php` — CSRF middleware
