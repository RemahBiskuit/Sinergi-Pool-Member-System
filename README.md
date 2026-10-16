# Sinergi Pool Member

Aplikasi member pool Hotel Sinergi berbasis Laravel 12 dengan MySQL dan USB NFC/RFID reader mode HID Keyboard Emulation.

## Menjalankan lokal

1. `composer install`
2. Salin `.env.example` menjadi `.env`
3. Isi koneksi MySQL lokal pada `.env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan serve`
7. Buka `http://127.0.0.1:8000`

## Deploy ke hosting

Pastikan hosting menyediakan PHP 8.2 atau lebih baru, Composer, MySQL, Apache dengan `mod_rewrite`, dan ekstensi PHP PDO MySQL.

1. Buat database dan user MySQL dari panel hosting.
2. Upload project ke server. Jangan jadikan folder project sebagai document root kecuali hosting mengizinkan aturan `public`.
3. Jadikan folder `public` sebagai document root domain/subdomain. Jika shared hosting selalu memakai `public_html`, salin isi folder `public` ke `public_html` dan simpan folder aplikasi lainnya di luar `public_html`; sesuaikan path pada `public/index.php` jika struktur folder berbeda.
4. Upload `.env` production berdasarkan `.env.example`.
5. Isi `APP_URL` dengan URL HTTPS dan isi `DB_DATABASE`, `DB_USERNAME`, serta `DB_PASSWORD` dari hosting.
6. Jalankan dari folder project: `composer install --no-dev --optimize-autoloader`.
7. Jalankan `php artisan key:generate` hanya jika `APP_KEY` belum ada.
8. Jalankan `php artisan migrate --force`.
9. Pastikan `bootstrap/cache` dan `storage` dapat ditulis oleh web server.
10. Set `APP_DEBUG=false`, lalu jalankan `php artisan config:cache` dan `php artisan view:cache`.
11. Ganti `ADMIN_USERNAME` dan `ADMIN_PASSWORD` dengan kredensial admin yang kuat.

Jika hosting tidak menyediakan akses SSH/terminal, jalankan Composer dan Artisan di komputer lokal, lalu upload folder `vendor`, hasil cache, dan file project ke hosting. Migration tetap dapat dijalankan melalui terminal hosting atau tool database provider.

### Menampilkan halaman Under Maintenance

Untuk menampilkan halaman maintenance ke pengunjung selama proses perbaikan:

1. Pastikan file `resources/views/maintenance.blade.php` dan `public/images/sinergi-logo.png` sudah ter-upload.
2. Buka terminal hosting/SSH, lalu masuk ke folder utama aplikasi Laravel (folder yang berisi file `artisan`).
3. Aktifkan maintenance mode:

   ```bash
   php artisan down --render=maintenance
   ```

   Perintah ini menampilkan halaman Under Maintenance untuk permintaan website dan mengembalikan status HTTP 503. URL `https://domain-anda.com/maintenance` juga dapat dibuka langsung tanpa mengaktifkan maintenance mode.
4. Setelah pemeliharaan selesai, buka kembali website:

   ```bash
   php artisan up
   ```

Jika admin perlu mengakses website saat maintenance aktif, aktifkan dengan secret bypass:

```bash
php artisan down --render=maintenance --with-secret
```

Gunakan URL secret yang ditampilkan di terminal hosting untuk melewati maintenance mode. Jangan bagikan URL tersebut kepada pengunjung. Pastikan `APP_URL` di `.env` mengarah ke domain production dan folder `storage` dapat ditulis oleh web server. Jika hosting tidak menyediakan akses terminal/SSH, buka `/maintenance` secara langsung; untuk mengalihkan semua URL ke halaman tersebut, atur maintenance mode melalui terminal hosting atau minta bantuan provider.

## Reader

Reader USB HID Keyboard Emulation mengetik UID ke input kartu. Klik `Scan Kartu` untuk membuka konfirmasi; klik `Masuk` untuk mencatat scan dan mengurangi kuota paket. Reader CCID/PCSC memerlukan local bridge.
