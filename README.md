<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## User Product App
### Instalasi

1. masukkan git clone https://github.com/calvinnickholaa/user-product-app.git
2. Run composer update pada cmd di folder project. kalo error coba composer install --ignore-platform-reqs or composer update --ignore-platform-reqs
3. Copy file .env "cp .env.examples .env"
4. Atur file .env (Nama aplikasi, nama db dsb)
5. Run php artisan key:generate
6. Buat Database sesuai variable .env yang dibuat tadi
7. Setelah Kalian mengatur .env yang ada jangan lupa untuk menjalankan command berikut "php artisan migrate:fresh --seed" gunanya untuk merefresh database yang ada dan memberikan seed untuk user login and register (biar bisa di masukin ke dalam table users)
Happy Coding!
