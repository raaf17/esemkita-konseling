# 🏫 Bimbingan Konseling Management System

Proyek ini adalah **aplikasi manajemen bimbingan konseling** berbasis web yang dibangun menggunakan **CodeIgniter 4 (HMVC Structure)**.  
Aplikasi ini ditujukan untuk membantu sekolah/guru BK dalam mengelola data siswa, layanan konseling, laporan, hingga manajemen akses pengguna.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi & Hak Akses**
  - Login, lupa password, reset password
  - Manajemen role & permission berbasis menu/submenu

- 👩‍🏫 **Manajemen Data Guru & Siswa**
  - CRUD Guru, Siswa, Jurusan, Kelas
  - Mutasi siswa

- 🧑‍🤝‍🧑 **Layanan Konseling**
  - Konseling individu & kelompok
  - Kunjungan rumah
  - Pendataan masalah siswa
  - Pencatatan layanan & tindak lanjut

- 📊 **Laporan & Monitoring**
  - Laporan kegiatan konseling
  - Aktivitas login pengguna
  - Dashboard ringkasan data

- ⚙️ **Fitur Pendukung**
  - Manajemen profil pengguna
  - Manajemen setting aplikasi
  - Upload & generate laporan dalam format PDF
  - Quiz & Materi pembelajaran tambahan

---

## 📂 Struktur Proyek

Proyek ini menggunakan **modular structure (HMVC)**. Beberapa module utama:

```
app/
├─ Modules/
│  ├─ Auth/           → Login, reset password
│  ├─ Dashboard/      → Dashboard utama
│  ├─ Guru/           → CRUD Guru
│  ├─ Siswa/          → CRUD Siswa
│  ├─ Kelas/          → CRUD Kelas
│  ├─ Jurusan/        → CRUD Jurusan
│  ├─ Konseling/      → Manajemen konseling siswa
│  ├─ KunjunganRumah/ → Data kunjungan rumah siswa
│  ├─ Masalah/        → Data masalah siswa
│  ├─ Layanan/        → Jenis layanan konseling
│  ├─ Laporan/        → Laporan kegiatan
│  ├─ HakAkses/       → Role & Permission
│  ├─ Profile/        → Profil pengguna
│  ├─ Users/          → Manajemen user
│  └─ Settings/       → Pengaturan sistem
```

Selain itu tersedia:
- `app/Helpers` → helper custom (`CIFunctions`, `CIMail`, `CIPdf`)  
- `app/Libraries` → library custom (`CIAuth`, `GenerateUuid`, `Hash`)  
- `public/assets` → static files (CSS, JS, images)  

---

## 🛠️ Teknologi

- **Framework**: [CodeIgniter 4](https://codeigniter.com/)  
- **Database**: MySQL/MariaDB  
- **Frontend Assets**: Bootstrap, jQuery, DataTables, dll.  
- **Library Tambahan**: PhpSpreadsheet, dompdf, ramsey/uuid  

---

## 🚀 Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/username/project-bk.git
   cd project-bk
   ```

2. **Install dependencies dengan Composer**
   ```bash
   composer install
   ```

3. **Salin file env**
   ```bash
   cp env .env
   ```

4. **Konfigurasi environment (.env)**
   ```env
   app.baseURL = 'http://localhost:8080'
   database.default.hostname = localhost
   database.default.database = nama_database
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   ```

5. **Jalankan server**
   ```bash
   php spark serve
   ```

6. Akses di browser:  
   👉 `http://localhost:8080`

---

## 👨‍💻 Developer Notes

- Struktur menggunakan **HMVC** agar modular & scalable
- Gunakan `php spark migrate` untuk menjalankan migrasi database
- Hak akses berbasis **role & url** yang diatur melalui modul **HakAkses**

---

## 📜 License

[MIT License](LICENSE)
