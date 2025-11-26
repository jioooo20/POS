# Feature Tests - Authentication

## Overview
Dokumentasi lengkap untuk feature tests autentikasi pada aplikasi POS Laravel 10. Test suite ini memastikan semua fitur autentikasi (registrasi, login, dan logout) berfungsi dengan baik.

## Setup & Configuration

### Database Testing
Tests menggunakan **SQLite in-memory database** untuk isolasi dan kecepatan. Konfigurasi di `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### Environment Testing
- `APP_ENV`: testing
- `APP_KEY`: Encrypted key untuk testing
- `BCRYPT_ROUNDS`: 4 (untuk mempercepat testing)
- `SESSION_DRIVER`: array
- `CACHE_DRIVER`: array

## Test File: AuthTest.php

### Total Coverage
- **14 Test Cases**
- **35 Assertions**
- **100% Pass Rate** ✅

### Test Cases Detail

#### 1. Registration Tests (4 tests)

##### ✓ test_register_page_can_be_accessed
**Tujuan**: Memastikan halaman registrasi dapat diakses dengan benar.

**Assertions**:
- Status HTTP 200
- View `auth.regis` di-render
- Variable `levels` tersedia di view

**Endpoint**: `GET /register`

---

##### ✓ test_user_can_register_with_valid_data
**Tujuan**: Memastikan user baru dapat mendaftar dengan data yang valid.

**Test Data**:
```php
[
    'username' => 'testuser',
    'password' => 'password123',
    'nama' => 'Test User',
    'level_id' => 1
]
```

**Assertions**:
- Response JSON status `true`
- Message "User berhasil ditambahkan"
- Data tersimpan di database `m_user`

**Endpoint**: `POST /register`

---

##### ✓ test_registration_fails_with_duplicate_username
**Tujuan**: Memastikan registrasi gagal jika username sudah ada.

**Scenario**:
1. Buat user dengan username 'existinguser'
2. Coba registrasi dengan username yang sama

**Assertions**:
- HTTP status 400
- Response JSON status `false`
- Message "Username telah digunakan"

**Endpoint**: `POST /register`

---

##### ✓ test_registration_fails_with_invalid_data
**Tujuan**: Memastikan validasi data registrasi bekerja.

**Invalid Data**:
- Username terlalu pendek (< 3 karakter)
- Password terlalu pendek (< 6 karakter)
- Nama kosong
- Level ID kosong

**Assertions**:
- HTTP status 422 (Validation Error)

**Endpoint**: `POST /register`

---

#### 2. Login Tests (6 tests)

##### ✓ test_login_page_can_be_accessed
**Tujuan**: Memastikan halaman login dapat diakses.

**Assertions**:
- Status HTTP 200
- View `auth.login` di-render

**Endpoint**: `GET /login`

---

##### ✓ test_authenticated_user_redirected_from_login
**Tujuan**: User yang sudah login tidak perlu mengakses halaman login.

**Scenario**:
- User sudah terautentikasi
- Mengakses `/login`

**Assertions**:
- Redirect ke `/` (dashboard)

**Endpoint**: `GET /login`

---

##### ✓ test_user_can_login_with_correct_credentials
**Tujuan**: User dapat login dengan kredensial yang benar.

**Test Credentials**:
```php
[
    'username' => 'testuser',
    'password' => 'password123'
]
```

**Assertions**:
- Response JSON status `true`
- Message "Login Berhasil"
- Redirect URL ke dashboard
- User terautentikasi (`assertAuthenticatedAs`)

**Endpoint**: `POST /login`

---

##### ✓ test_login_fails_with_incorrect_credentials
**Tujuan**: Login gagal dengan password salah.

**Scenario**:
- Username benar
- Password salah

**Assertions**:
- Response JSON status `false`
- Message "Login Gagal"
- User tetap guest (`assertGuest`)

**Endpoint**: `POST /login`

---

##### ✓ test_login_fails_with_nonexistent_user
**Tujuan**: Login gagal jika user tidak ada.

**Scenario**:
- Username tidak terdaftar

**Assertions**:
- Response JSON status `false`
- Message "Login Gagal"
- User tetap guest

**Endpoint**: `POST /login`

---

##### ✓ test_password_is_hashed_when_creating_user
**Tujuan**: Memastikan password di-hash saat registrasi, tidak disimpan plain text.

**Assertions**:
- Password di database tidak sama dengan plain text
- `Hash::check()` berhasil verifikasi password

**Endpoint**: `POST /register`

---

#### 3. Logout Tests (2 tests)

##### ✓ test_user_can_logout
**Tujuan**: User terautentikasi dapat logout.

**Scenario**:
- User login
- Akses endpoint logout

**Assertions**:
- Redirect ke `/login`
- User menjadi guest (`assertGuest`)

**Endpoint**: `GET /logout`

---

##### ✓ test_guest_cannot_logout
**Tujuan**: Guest tidak dapat mengakses endpoint logout (protected by auth middleware).

**Assertions**:
- Redirect ke `/login`

**Endpoint**: `GET /logout`

---

#### 4. Authorization Tests (2 tests)

##### ✓ test_authenticated_user_can_access_dashboard
**Tujuan**: User yang login dapat akses dashboard.

**Assertions**:
- HTTP status 200

**Endpoint**: `GET /`

---

##### ✓ test_guest_cannot_access_dashboard
**Tujuan**: Guest tidak dapat akses dashboard (protected by auth middleware).

**Assertions**:
- Redirect ke `/login`

**Endpoint**: `GET /`

---

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
php artisan test --filter=AuthTest
```

### Run Single Test
```bash
php artisan test --filter=test_user_can_login_with_correct_credentials
```

### Run with Coverage (if enabled)
```bash
php artisan test --coverage
```

## Test Results

```
PASS  Tests\Feature\AuthTest
✓ register page can be accessed
✓ user can register with valid data
✓ registration fails with duplicate username
✓ registration fails with invalid data
✓ login page can be accessed
✓ authenticated user redirected from login
✓ user can login with correct credentials
✓ login fails with incorrect credentials
✓ login fails with nonexistent user
✓ user can logout
✓ guest cannot logout
✓ authenticated user can access dashboard
✓ guest cannot access dashboard
✓ password is hashed when creating user

Tests:    14 passed (35 assertions)
Duration: ~1.7s
```

## Test Data Seeding

### Level Data
Tests otomatis membuat data level di `setUp()`:

```php
- Level ID 1: ADM (Administrator)
- Level ID 2: MNG (Manager)
- Level ID 3: STF (Staff/Kasir)
```

### Test Users
Setiap test membuat user sendiri dengan `UserModel::create()` untuk isolasi data.

## Models & Dependencies

### Models Used
- `UserModel`: Model utama untuk autentikasi
- `LevelModel`: Model untuk role/level user

### Traits Used
- `RefreshDatabase`: Reset database setiap test untuk isolasi

### Facades Used
- `Hash`: Password hashing
- `Auth`: Authentication

## Routes Tested

| Method | Route | Middleware | Controller Method |
|--------|-------|------------|-------------------|
| GET | `/register` | - | `AuthController@register` |
| POST | `/register` | - | `AuthController@postregister` |
| GET | `/login` | - | `AuthController@login` |
| POST | `/login` | - | `AuthController@postlogin` |
| GET | `/logout` | auth | `AuthController@logout` |
| GET | `/` | auth | `WelcomeController@index` |

## Security Features Tested

### ✅ Password Hashing
- Password tidak disimpan dalam plain text
- Menggunakan bcrypt hashing
- Verifikasi dengan `Hash::check()`

### ✅ Validation
- Username: 3-20 karakter
- Password: 6-20 karakter
- Nama: required, max 100 karakter
- Level ID: required

### ✅ Authentication
- Session-based authentication
- Middleware protection untuk route protected
- Automatic redirect untuk guest users

### ✅ Duplicate Prevention
- Username harus unique
- Validasi sebelum insert database

## Maintenance & Updates

### Adding New Test
1. Buka `tests/Feature/AuthTest.php`
2. Tambahkan method dengan prefix `test_`
3. Gunakan `RefreshDatabase` trait untuk isolasi
4. Run test: `php artisan test --filter=AuthTest`

### Updating Test Data
Jika struktur database berubah:
1. Update `setUp()` method untuk seeding data
2. Update assertions sesuai struktur baru
3. Update test user creation

