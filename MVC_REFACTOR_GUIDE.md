# Refactor ke MVC Pattern - Update Guide

## ✅ **Refactoring yang telah dilakukan:**

### 1. **Struktur Baru MVC:**
```
public/
├── index.php          # Single Entry Point
├── api.php           # API Entry Point (tetap dipertahankan)
├── .htaccess         # URL Rewriting rules
└── assets/, css/, js/

app/
├── controllers/       # Semua controller
├── views/            # Template views
├── models/           # Model classes
└── config/
    ├── Router.php    # Enhanced routing system
    └── ...
```

### 2. **File yang dihapus (Cleanup):**
- ❌ `debug-*.php` (5 files)
- ❌ `test-*.php` (7 files) 
- ❌ `fix-*.php` (2 files)
- ❌ `check-*.php`, `setup-db.php`, dll
- ❌ Semua admin entry points (`admin-*.php`)
- ❌ Semua public entry points (`alumni.php`, `contact.php`, dll)

### 3. **Router Enhancement:**
- ✅ Single entry point dengan URL rewriting
- ✅ Clean URLs tanpa `.php`
- ✅ Parameter extraction (`{id}`)
- ✅ Method-based routing (GET, POST, PUT, DELETE)

### 4. **Controller Pattern:**
- ✅ Base Controller dengan view rendering
- ✅ Automatic layout detection (admin vs public)
- ✅ Flash messages support
- ✅ JSON response untuk API
- ✅ Redirect functionality

## 🚀 **URL Mapping Baru:**

### Public Pages:
- `/` → HomeController@index
- `/alumni` → AlumniController@index
- `/alumni/{id}` → AlumniController@detail
- `/contact` → ContactController@index
- `/jadwal` → JadwalPraktikumController@index
- `/laboratorium` → InformasiLabController@index
- dst...

### Admin Pages:
- `/admin` → DashboardController@index
- `/admin/alumni` → AlumniController@adminIndex
- `/admin/alumni/create` → AlumniController@create
- `/admin/alumni/{id}/edit` → AlumniController@edit
- dst...

### API Endpoints:
- `/api/alumni` → AlumniController@apiIndex
- `/api/alumni/{id}` → AlumniController@apiShow
- `/api/health` → HealthController@check
- dst...

## 📝 **Cara Menggunakan:**

1. **Tambah Route baru** di `Router.php`:
   ```php
   $this->get('/new-page', 'NewController', 'index');
   ```

2. **Buat Controller baru**:
   ```php
   class NewController extends Controller {
       public function index($params = []) {
           $this->view('new/index', ['data' => $data]);
       }
   }
   ```

3. **Buat View** di `app/views/new/index.php`

## 🎯 **Keuntungan MVC Pattern:**

- ✅ **Clean URLs**: `/alumni/123` bukan `detail-alumni.php?id=123`
- ✅ **Single Entry Point**: Semua request melalui `index.php`
- ✅ **Separation of Concerns**: Logic terpisah dari presentation
- ✅ **Maintainable Code**: Lebih mudah maintain dan extend
- ✅ **Security**: Block direct access ke file PHP
- ✅ **SEO Friendly**: Clean URLs

## ⚠️ **Breaking Changes:**
- Semua URL lama dengan `.php` sudah tidak bisa diakses
- File entry point lama sudah dihapus
- Harus menggunakan routing system yang baru