# 📖 PENJELASAN DETAIL: 7 FILE PENTING UNTUK PRESENTASI

---

## 1️⃣ ROUTER LOGIC (`app/config/Router.php`)

### **Fungsi & Konsep**
Router adalah "dispatcher" yang mengubah **URL menjadi Controller & Method**.

**Analogi**: 
- URL seperti **alamat surat** → Router adalah **postman** → Mengantar ke Controller yang tepat

### **Cara Kerja Router**

```
User Input URL
    ↓
Router menangkap path (getPath())
    ↓
Router membandingkan dengan route list (defineRoutes())
    ↓
Jika match → Jalankan Controller@Method
    ↓
Jika tidak match → Error 404
```

### **Contoh Routes yang Didefinisikan**

```php
// Public Routes
GET  /alumni              → AlumniController@index()
GET  /alumni/{id}        → AlumniController@detail($id)
GET  /asisten            → AsistenController@index()
GET  /jadwal             → JadwalPraktikumController@index()

// Admin Routes (Protected)
GET  /admin/alumni       → AlumniController@adminIndex()
POST /admin/alumni       → AlumniController@store()
PUT  /admin/alumni/{id}  → AlumniController@update($id)
DELETE /admin/alumni/{id}→ AlumniController@delete($id)

// Auth Routes
GET  /login              → AuthController@login()
POST /login              → AuthController@authenticate()
GET  /logout             → AuthController@logout()
```

### **Implementasi Method Penting**

#### 1. **getPath()** - Ekstrak URL dari request
```php
private function getPath() {
    // Cek source URL dari berbagai tempat:
    // 1. Parameter 'route' dari RewriteRule (.htaccess)
    // 2. PATH_INFO dari server
    // 3. REQUEST_URI dari request
    
    // Normalisasi dan return clean path
    return '/' . trim($path, '/');
}
```
**Keuntungan**: Deteksi otomatis, bekerja di XAMPP maupun server normal.

#### 2. **defineRoutes()** - Definisi semua route aplikasi
```php
private function defineRoutes() {
    // Daftar semua route yang available di aplikasi
    $this->get('/', 'HomeController', 'index');
    $this->get('/alumni', 'AlumniController', 'index');
    $this->post('/admin/alumni', 'AlumniController', 'store');
    // ... dst
}
```

#### 3. **dispatch()** - Eksekusi controller & method
```php
public function dispatch() {
    // 1. Load route definitions
    $this->defineRoutes();
    
    // 2. Cocokkan URL dengan route
    // 3. Ekstrak parameter dari URL (misal {id})
    // 4. Load controller class
    // 5. Panggil method dengan parameter
    
    // Misal untuk /alumni/5 → AlumniController->detail(['id' => 5])
}
```

### **Key Features**

✅ **Clean URLs**: `/alumni` bukan `/alumni.php`  
✅ **Dynamic Parameters**: `/alumni/{id}` → param `id` otomatis diekstrak  
✅ **HTTP Methods**: Mendukung GET, POST, PUT, DELETE  
✅ **Lazy Loading**: Route hanya didefinisikan saat diperlukan  
✅ **Auto Path Detection**: Bekerja di XAMPP & server production  

---

## 2️⃣ BASE CONTROLLER (`app/controllers/Controller.php`)

### **Fungsi & Konsep**
Base Controller adalah **parent class** untuk semua controller. Menyediakan:
- Template method untuk load view
- Response handling (JSON, redirect, error)
- Input validation helpers

### **Arsitektur**
```
Controller (Base Class)
├── view()          → Load view dengan layout
├── partial()       → Load view tanpa layout (AJAX)
├── redirect()      → Redirect ke URL lain
├── response()      → Return JSON response
├── success()       → Success JSON response
├── error()         → Error JSON response
├── getJson()       → Parse JSON request body
├── getPost()       → Get POST data
├── getGet()        → Get GET data
└── setFlash()      → Set flash message (session)
```

### **Method Penting**

#### 1. **view($view, $data = [])**
```php
protected function view($view, $data = []) {
    // 1. Extract data → konversi array menjadi variabel PHP
    extract($data);
    // Sekarang $data['alumni'] bisa diakses sebagai $alumni
    
    // 2. Tentukan CSS halaman berdasarkan folder view
    if (strpos($view, 'alumni/') === 0) {
        $pageCss = 'alumni.css';
    }
    
    // 3. Deteksi apakah admin atau public page
    $isAdmin = (strpos($requestUri, '/admin') !== false);
    
    // 4. Load template
    if ($isAdmin) {
        require VIEW_PATH . '/admin/templates/header.php';
    } else {
        require VIEW_PATH . '/templates/header.php';
    }
    
    // 5. Load view content
    require VIEW_PATH . '/' . $view . '.php';
    
    // 6. Load footer
    require VIEW_PATH . '/templates/footer.php';
}
```

**Flow Visualisasi**:
```
view('alumni/index', ['alumni' => $data])
    ↓
extract() → $alumni variable tersedia
    ↓
Determine CSS → alumni.css
    ↓
Load header.php
    ↓
Load alumni/index.php (bisa akses $alumni)
    ↓
Load footer.php
```

#### 2. **response($data, $status = 200)**
```php
protected function response($data, $status = 200) {
    // Set header JSON
    header('Content-Type: application/json');
    http_response_code($status);
    
    // Echo JSON & stop execution
    echo json_encode($data);
    exit;
}
```
**Digunakan untuk API**: `/api/alumni` → return JSON list

#### 3. **redirect($url)**
```php
protected function redirect($url) {
    // Jika URL relative, append BASE_URL
    if (strpos($url, 'http') !== 0) {
        $url = BASE_URL . '/' . ltrim($url, '/');
    }
    header('Location: ' . $url);
    exit;
}
```
**Contoh**: `$this->redirect('/admin/alumni')` → Pindah ke halaman admin alumni

#### 4. **success() & error()**
```php
protected function success($data = null, $message = 'Success', $status = 200) {
    $this->response([
        'status' => 'success',
        'code' => $status,
        'message' => $message,
        'data' => $data
    ], $status);
}

protected function error($message = 'Error', $data = null, $status = 400) {
    $this->response([
        'status' => 'error',
        'code' => $status,
        'message' => $message,
        'data' => $data
    ], $status);
}
```
**Contoh Response**:
```json
{
    "status": "success",
    "code": 200,
    "message": "Alumni berhasil ditambahkan",
    "data": {"id": 1}
}
```

### **Key Features**

✅ **Template Pattern**: Setiap controller inherit base functionality  
✅ **Auto Layout Detection**: Otomatis pilih layout (admin/public)  
✅ **JSON Response**: Siap untuk AJAX & API  
✅ **Input Helpers**: getPost(), getGet(), getJson()  
✅ **Error Handling**: Success/error response standardized  

---

## 3️⃣ BASE MODEL (`app/models/Model.php`)

### **Fungsi & Konsep**
Base Model menyediakan **CRUD operations** (Create, Read, Update, Delete) yang bisa digunakan semua model.

**Pattern**: Setiap model merepresentasikan **satu tabel** di database.

```php
// Contoh:
class AlumniModel extends Model {
    protected $table = 'alumni';
}

class AsistenModel extends Model {
    protected $table = 'asisten';
}
```

### **CRUD Operations**

#### 1. **READ - getAll()**
```php
public function getAll() {
    $query = "SELECT * FROM " . $this->table;
    $result = $this->db->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}
```
**Contoh**: `$alumni = $model->getAll();` → Return array semua alumni

#### 2. **READ - getById($id)**
```php
public function getById($id, $idColumn = 'id') {
    $query = "SELECT * FROM " . $this->table . " WHERE " . $idColumn . " = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
```
**Security**: Menggunakan **prepared statement** → Prevent SQL injection  
**Contoh**: `$alumni = $model->getById(5);` → Return 1 baris alumni dengan id=5

#### 3. **CREATE - insert($data)**
```php
public function insert($data) {
    // Buat string column names & placeholders
    $columns = implode(", ", array_keys($data));      // 'nama, angkatan, email'
    $values = implode(", ", array_fill(0, count($data), "?")); // '?, ?, ?'
    
    $query = "INSERT INTO " . $this->table . " (" . $columns . ") VALUES (" . $values . ")";
    $stmt = $this->db->prepare($query);
    
    // Bind semua values
    $types = str_repeat("s", count($data)); // 'sss' = 3 string
    $stmt->bind_param($types, ...array_values($data));
    
    return $stmt->execute();
}
```
**Contoh**:
```php
$data = [
    'nama' => 'Arisa Tien',
    'angkatan' => 2020,
    'email' => 'arisa@umi.ac.id'
];
$model->insert($data);
// Generated Query: INSERT INTO alumni (nama, angkatan, email) VALUES (?, ?, ?)
```

#### 4. **UPDATE - update($id, $data)**
```php
public function update($id, $data, $idColumn = 'id') {
    // Buat SET clause: "nama = ?, angkatan = ?"
    $set = "";
    foreach ($data as $column => $value) {
        $set .= $column . " = ?, ";
    }
    $set = rtrim($set, ", ");
    
    $query = "UPDATE " . $this->table . " SET " . $set . " WHERE " . $idColumn . " = ?";
    $stmt = $this->db->prepare($query);
    
    // Bind: values + id di akhir
    $types = str_repeat("s", count($data)) . "i";
    $values = array_merge(array_values($data), [$id]);
    $stmt->bind_param($types, ...$values);
    
    return $stmt->execute();
}
```
**Contoh**:
```php
$data = ['nama' => 'Arisa Tien Updated'];
$model->update(5, $data);
// Generated Query: UPDATE alumni SET nama = ? WHERE id = ?
```

#### 5. **DELETE - delete($id)**
```php
public function delete($id, $idColumn = 'id') {
    $query = "DELETE FROM " . $this->table . " WHERE " . $idColumn . " = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
```
**Contoh**: `$model->delete(5);` → Hapus alumni dengan id=5

#### 6. **UTILITY Methods**
```php
public function getLastInsertId() {
    return $this->db->insert_id;  // Get ID terakhir yang diinsert
}

public function countAll() {
    // SELECT COUNT(*) as total FROM table
    return $result->fetch_assoc()['total'];
}
```

### **Keamanan: Prepared Statements**

**Tanpa Prepared Statements (BAHAYA):**
```php
// ❌ JANGAN PAKAI - Rentan SQL Injection
$query = "SELECT * FROM alumni WHERE id = " . $_GET['id'];
```

**Dengan Prepared Statements (AMAN):**
```php
// ✅ AMAN - Parameter dipisah dari query
$query = "SELECT * FROM alumni WHERE id = ?";
$stmt = $this->db->prepare($query);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
```

Attacker tidak bisa inject SQL karena nilai sudah terpisah dari query structure.

### **Key Features**

✅ **DRY (Don't Repeat Yourself)**: CRUD logic di satu tempat  
✅ **Prepared Statements**: Secure dari SQL injection  
✅ **Flexible**: Bisa customize WHERE clause di child model  
✅ **Type Safety**: bind_param("i", ...) → int, "s" → string  

---

## 4️⃣ AUTH SYSTEM (`AuthController.php` + `AuthMiddleware.php`)

### **Authentication Flow**

```
User Login
    ↓
Input username & password
    ↓
POST /login → AuthController@authenticate()
    ↓
Query database: UserModel::getByUsername($username)
    ↓
Verify password_verify($input_password, $hashed_password)
    ↓
SET SESSION ['user_id', 'username', 'role', 'status']
    ↓
Redirect to /admin (dashboard)
```

### **AuthController - Login Logic**

```php
public function authenticate() {
    // 1. VALIDASI INPUT
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $this->setFlash('error', 'Username dan Password wajib diisi.');
        $this->redirect(PUBLIC_URL . '/iclabs-login');
        return;
    }
    
    // 2. CARI USER DI DATABASE
    $user = $this->userModel->getByUsername($username);
    
    // 3. VERIFIKASI PASSWORD
    // Menggunakan password_hash/password_verify (Bcrypt)
    if (!$user || !password_verify($password, $user['password'])) {
        // Generic error untuk security (prevent user enumeration)
        $this->setFlash('error', 'Username atau Password salah.');
        $this->redirect(PUBLIC_URL . '/iclabs-login');
        return;
    }
    
    // 4. LOGIN SUKSES - SET SESSION
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['status'] = "login";
    
    // 5. UPDATE LAST LOGIN
    $this->userModel->updateLastLogin($user['id']);
    
    // 6. REDIRECT KE DASHBOARD
    $this->redirect(PUBLIC_URL . '/admin');
}
```

### **Password Security: Bcrypt**

**Bagaimana password disimpan:**

```php
// Saat registrasi/reset password:
$hashed_password = password_hash($plaintext_password, PASSWORD_BCRYPT);
// Contoh: 'pass123' → '$2y$10$...kh7F4Sl9Z0qO1mP2nQ3'
// Simpan $hashed_password ke database
```

**Saat login:**
```php
$user = getFromDatabase();
if (password_verify($_POST['password'], $user['password'])) {
    // Password cocok!
    login_user();
}
```

**Keuntungan Bcrypt:**
- ✅ One-way hashing (tidak bisa decode)
- ✅ Slow by design (prevent brute force)
- ✅ Salt included (same password = different hash)
- ✅ Industry standard

### **AuthMiddleware - Protect Routes**

```php
class AuthMiddleware {
    public static function check() {
        // 1. Check apakah session user_id ada
        if (!isset($_SESSION['user_id'])) {
            // 2. Jika API request → return 401 JSON
            if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                exit;
            }
            
            // 3. Jika halaman biasa → redirect ke login
            header('Location: ' . PUBLIC_URL . '/login');
            exit;
        }
    }
}
```

**Penggunaan:**
```php
// Di Router saat define protected routes
$this->get('/admin/alumni', 'AlumniController', 'adminIndex');
// Middleware check dijalankan sebelum controller method

// Atau bisa di public/index.php:
if (strpos($route, '/admin') === 0) {
    AuthMiddleware::check();
}
```

### **Generic Error Message (Security Best Practice)**

❌ **JANGAN PAKAI:**
```
"Username tidak terdaftar"
"Password yang Anda input salah"
```

✅ **PAKAI:**
```
"Username atau Password yang Anda masukkan salah"
```

**Alasan**: Prevent attacker mencari list username yang valid (user enumeration).

### **Key Features**

✅ **Session-based**: Simpan user state di server (`$_SESSION`)  
✅ **Bcrypt Hashing**: Password aman, tidak bisa di-reverse  
✅ **Generic Errors**: Tidak membocorkan info username valid  
✅ **Middleware Protection**: Admin routes require login  
✅ **Last Login Tracking**: Audit trail untuk security  

---

## 5️⃣ SAMPLE CONTROLLER - Alumni (`AlumniController.php`)

### **Struktur Controller**

```php
class AlumniController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new AlumniModel();
    }
    
    // Public methods (accessible via routing)
    public function index()           // GET /alumni
    public function detail()          // GET /alumni/{id}
    public function adminIndex()      // GET /admin/alumni
    public function create()          // GET /admin/alumni/create
    public function edit()            // GET /admin/alumni/{id}/edit
    public function store()           // POST /admin/alumni
    public function update()          // PUT /admin/alumni/{id}
    public function delete()          // DELETE /admin/alumni/{id}
}
```

### **Method Detail**

#### 1. **index() - List Alumni (Public)**
```php
public function index($params = []) {
    // 1. Query database
    $data = $this->model->getAll();
    
    // 2. Pass ke view
    $this->view('alumni/alumni', ['alumni' => $data]);
}
```
**Route**: `GET /alumni`  
**View**: `app/views/alumni/alumni.php`  
**Output**: HTML page dengan list alumni

#### 2. **detail() - Detail Alumni (Public)**
```php
public function detail($params = []) {
    $id = $params['id'] ?? null;
    if (!$id) {
        $this->redirect('/alumni');  // Redirect jika id kosong
        return;
    }
    
    // 1. Query alumni by id
    $alumniRow = $this->model->getById((int)$id, 'id');
    
    // 2. Validasi data ada
    if (!$alumniRow) {
        $this->redirect('/alumni');  // Jika tidak ditemukan, redirect
        return;
    }
    
    // 3. Format data (parse skills dari string ke array)
    $skills = !empty($alumniRow['keahlian']) 
        ? array_map('trim', explode(',', $alumniRow['keahlian'])) 
        : [];
    
    $alumni = [
        'nama' => $alumniRow['nama'],
        'angkatan' => $alumniRow['angkatan'],
        'divisi' => $alumniRow['divisi'] ?? 'Asisten Lab',
        'mata_kuliah' => $alumniRow['mata_kuliah'] ?? '-',
        'foto' => $alumniRow['foto'],
        'email' => $alumniRow['email'],
        'testimoni' => $alumniRow['kesan_pesan'] ?? 'Tidak ada kesan pesan.',
        'skills' => $skills
    ];
    
    // 4. Render detail view
    $this->view('alumni/detail', ['alumni' => $alumni]);
}
```
**Route**: `GET /alumni/{id}`  
**Proses**: ID dari URL → Query DB → Format data → Render view

#### 3. **store() - Create Alumni (Admin)**
```php
public function store() {
    try {
        // 1. COLLECT INPUT
        $input = [
            'nama' => $_POST['nama'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'divisi' => $_POST['divisi'] ?? '',
            'mata_kuliah' => $_POST['mata_kuliah'] ?? '',
            'kesan_pesan' => $_POST['kesan_pesan'] ?? '',
            'keahlian' => $_POST['keahlian'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        // 2. VALIDASI WAJIB
        if (empty($input['nama']) || empty($input['angkatan'])) {
            $this->error('Field nama dan angkatan wajib diisi', null, 400);
            return;
        }
        
        // 3. HANDLE FILE UPLOAD
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Tentukan folder upload
            $subFolder = 'alumni/';
            $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
            
            // Validasi format file
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($ext, $allowedExts)) {
                $this->error('Format file tidak didukung', null, 400);
                return;
            }
            
            // Generate nama file unik (prevent collision)
            $filename = Helper::generateFilename('alumni', $input['nama'], $ext);
            $target = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                $input['foto'] = $subFolder . $filename;
            } else {
                $this->error('Gagal mengupload file', null, 500);
                return;
            }
        }
        
        // 4. INSERT KE DATABASE
        $result = $this->model->insert($input);
        
        // 5. RETURN RESPONSE
        if ($result) {
            $this->success(
                ['id' => $this->model->getLastInsertId()],
                'Alumni berhasil ditambahkan',
                201
            );
        } else {
            $this->error('Gagal menambahkan alumni', null, 500);
        }
    } catch (Exception $e) {
        error_log('Alumni store error: ' . $e->getMessage());
        $this->error('Error: ' . $e->getMessage(), null, 500);
    }
}
```

**Flow:**
```
Form submission (POST /admin/alumni)
    ↓
Collect $_POST data
    ↓
Validate (nama & angkatan required)
    ↓
Handle file upload (foto)
    ↓
Insert to database
    ↓
Return success/error JSON
```

#### 4. **update() - Edit Alumni (Admin)**
```php
public function update($params) {
    try {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }
        
        // 1. GET OLD DATA (untuk delete old file nanti)
        $alumni = $this->model->getById($id, 'id');
        if (!$alumni) {
            $this->error('Alumni tidak ditemukan', null, 404);
            return;
        }
        
        // 2. COLLECT NEW INPUT
        $input = [
            'nama' => $_POST['nama'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'divisi' => $_POST['divisi'] ?? '',
            'mata_kuliah' => $_POST['mata_kuliah'] ?? '',
            'kesan_pesan' => $_POST['kesan_pesan'] ?? '',
            'keahlian' => $_POST['keahlian'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        // 3. HANDLE NEW FILE UPLOAD
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // ... same validation as store()
            
            // DELETE OLD FILE
            if (!empty($alumni['foto'])) {
                $baseUploadPath = dirname(__DIR__, 2) . '/public/assets/uploads/';
                $fullOldPath = $baseUploadPath . $alumni['foto'];
                
                if (file_exists($fullOldPath) && is_file($fullOldPath)) {
                    @unlink($fullOldPath);  // @ suppress error jika file tidak ada
                }
            }
            
            // ... upload new file
        }
        
        // 4. UPDATE DATABASE
        $result = $this->model->update($id, $input, 'id');
        
        // 5. RETURN RESPONSE
        if ($result) {
            $this->success([], 'Alumni berhasil diupdate', 200);
        } else {
            $this->error('Gagal mengupdate alumni', null, 500);
        }
    } catch (Exception $e) {
        error_log('Alumni update error: ' . $e->getMessage());
        $this->error('Error: ' . $e->getMessage(), null, 500);
    }
}
```

#### 5. **delete() - Delete Alumni (Admin)**
```php
public function delete($params) {
    $id = $params['id'] ?? null;
    if (!$id) {
        $this->error('ID tidak ditemukan', null, 400);
        return;
    }
    
    $result = $this->model->delete($id, 'id');
    
    if ($result) {
        $this->success([], 'Alumni deleted');
    } else {
        $this->error('Failed to delete alumni', null, 500);
    }
}
```

### **Key Features**

✅ **CRUD Complete**: Create, Read, Update, Delete  
✅ **File Upload**: Handle foto dengan validasi format & unique name  
✅ **Error Handling**: Try-catch untuk exception handling  
✅ **Input Validation**: Required fields check  
✅ **Redirect Logic**: Smart redirect jika data tidak valid  
✅ **Data Formatting**: Parse kompleks data sebelum display  

---

## 6️⃣ DATABASE SCHEMA (`database/sistem_manajemen_sumber_daya.sql`)

### **Table Utama & Relasi**

#### **1. Tabel `users`**
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- Hashed dengan Bcrypt
    role ENUM('admin', 'kepala_lab', 'asisten', 'member') DEFAULT 'member',
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
**Fungsi**: Authentication & authorization  
**Keamanan**: Password hashed, bukan plaintext

#### **2. Tabel `alumni`**
```sql
CREATE TABLE alumni (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(150) NOT NULL,
    angkatan YEAR(4) NOT NULL,
    divisi VARCHAR(100),
    mata_kuliah TEXT,  -- JSON array atau string comma-separated
    foto VARCHAR(255),
    kesan_pesan TEXT,
    keahlian TEXT,     -- Skills/expertise alumni
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```
**Fungsi**: Database alumni asisten lab  
**Data Real**: 28 alumni dari angkatan 2020-2021  
**Contoh Data**:
- Arisa Tien Hardianti, 2020, Basis Data, Keahlian: SQL, Pengajaran
- Nasrullah, 2021, Laravel, PHP, Web Development, Data Science, ML

#### **3. Tabel `asisten`**
```sql
CREATE TABLE asisten (
    idAsisten INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100),
    jabatan VARCHAR(100),
    lab VARCHAR(100),
    spesialisasi VARCHAR(255),
    bio TEXT,
    skills TEXT,       -- JSON array
    email VARCHAR(100),
    foto VARCHAR(255),
    statusAktif VARCHAR(20),  -- 'Asisten', 'CA' (Course Assistant)
    isKoordinator TINYINT(1) DEFAULT 0  -- Flag: 1 = koordinator
);
```
**Fungsi**: Data asisten lab aktif  
**Relasi**: isKoordinator = 1 → Koordinator Lab  
**Data Real**: 32 asisten aktif (mix Asisten & CA)

#### **4. Tabel `matakuliah`**
```sql
CREATE TABLE matakuliah (
    idMatakuliah INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    kode_mk VARCHAR(50),
    sks INT,
    status VARCHAR(50) DEFAULT 'Aktif'
);
```
**Data Real**: 15 mata kuliah (Algoritma, PBO, BD, Microcontroller, dll)

#### **5. Tabel `jadwalpraktikum`** ⭐ (TERBESAR)
```sql
CREATE TABLE jadwalpraktikum (
    idJadwal INT PRIMARY KEY AUTO_INCREMENT,
    idMatakuliah INT NOT NULL,  -- FK → matakuliah
    kelas VARCHAR(50),           -- A1, A2, B1, dll
    idLaboratorium INT NOT NULL, -- FK → laboratorium
    hari VARCHAR(20),            -- Senin, Selasa, dll
    waktuMulai TIME,
    waktuSelesai TIME,
    dosen VARCHAR(255),
    asisten1 VARCHAR(255),
    asisten2 VARCHAR(255),
    frekuensi VARCHAR(150),      -- TI_MICRO-5, TI_BD2-3, dll
    tanggal DATE,
    status VARCHAR(50) DEFAULT 'Aktif'
);
```
**Fungsi**: Master schedule praktikum  
**Data Real**: 30+ jadwal prakti kum dengan info lengkap  
**Join dengan**: matakuliah, asisten, laboratorium

#### **6. Tabel `laboratorium`**
```sql
CREATE TABLE laboratorium (
    idLaboratorium INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    kapasitas INT,
    fasilitas TEXT,
    status VARCHAR(50) DEFAULT 'Aktif'
);
```
**Data Real**: 
- Lab 23, 24, 26, 27, 29 (total 5 lab)

#### **7. Tabel `peraturan_lab`**
```sql
CREATE TABLE peraturan_lab (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255),
    deskripsi TEXT,
    kategori VARCHAR(50)  -- 'peraturan', 'sanksi', 'tata_tertib'
);
```

#### **8. Tabel `format_penulisan`**
```sql
CREATE TABLE format_penulisan (
    id_format INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    file VARCHAR(255),
    kategori ENUM('pedoman','unduhan'),
    link_external VARCHAR(255),
    tanggal_update DATE
);
```
**Data Real**: 7 pedoman penulisan (margin, watermark, dll)

### **Relasi antar Tabel**

```
jadwalpraktikum (Master Table)
├── idMatakuliah → matakuliah
├── idLaboratorium → laboratorium
├── asisten1, asisten2 → asisten (nama text, bukan FK)
└── dosen (text, bukan FK)

alumni
└── Standalone (no FK)

asisten
├── isKoordinator (flag, bukan FK)
└── Standalone

users
└── Standalone (session management)
```

**Note**: Beberapa relasi menggunakan teks (nama) daripada FK. Ini simplified design yang OK untuk project academic.

### **Key Features**

✅ **Normalized Schema**: Minimal redundancy  
✅ **Timestamps**: created_at, updated_at untuk audit trail  
✅ **Enums**: Type safety untuk kategori, status  
✅ **TEXT Fields**: Untuk JSON array (skills, mata_kuliah)  
✅ **Seed Data**: 100+ baris data real dari lab  

---

## 7️⃣ FRONTEND LOGIC (`public/js/main.js`)

### **Global JavaScript Utilities**

File ini dipanggil di **semua halaman** (dari footer.php) untuk:
- Mobile navigation
- UI interactions
- Keyboard shortcuts
- Scroll handling

### **Fitur-Fitur**

#### 1. **Mobile Menu Toggle (Hamburger Button)**
```javascript
const menuToggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");

if (menuToggle && navLinks) {
    menuToggle.addEventListener("click", function (e) {
        e.stopPropagation(); // Prevent click bubbling up
        
        // Toggle menu & icon animation
        navLinks.classList.toggle("active");
        menuToggle.classList.toggle("active");
    });
}
```
**Flow:**
```
User click hamburger
    ↓
Toggle .active class on navLinks
    ↓
CSS animates: hamburger icon & menu slide in/out
```

#### 2. **Mobile Dropdown Accordion**
```javascript
const dropdownToggles = document.querySelectorAll(".dropdown > a");

dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
        // Hanya aktif di mobile (width < 992px)
        if (window.innerWidth <= 992) {
            e.preventDefault();
            e.stopPropagation();
            
            const currentContent = this.nextElementSibling; // Submenu
            
            // ACCORDION: Close other dropdowns
            document.querySelectorAll(".dropdown-content").forEach((content) => {
                if (content !== currentContent) {
                    content.classList.remove("show");
                }
            });
            
            // Toggle current dropdown
            if (currentContent) {
                currentContent.classList.toggle("show");
            }
        }
    });
});
```
**Logika**: Hanya 1 dropdown bisa terbuka di mobile (accordion behavior)

#### 3. **Back to Top Button**
```javascript
const backToTopButton = document.getElementById("backToTop");

if (backToTopButton) {
    // Show button saat user scroll > 300px
    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            backToTopButton.classList.add("show");
        } else {
            backToTopButton.classList.remove("show");
        }
    });
    
    // Smooth scroll ke top saat di-klik
    backToTopButton.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
}
```

#### 4. **Keyboard Shortcut: Ctrl + Shift + L + ;**
```javascript
const keysPressed = {};

document.addEventListener("keydown", function (e) {
    keysPressed[e.code] = true;
    
    // Check kombinasi tombol
    if (
        e.ctrlKey &&
        e.shiftKey &&
        keysPressed["KeyL"] &&
        keysPressed["Semicolon"]
    ) {
        e.preventDefault();
        window.location.href = "/iclabs-login";
    }
});

document.addEventListener("keyup", function (e) {
    keysPressed[e.code] = false;
});
```
**Kegunaan**: Admin hidden shortcut ke login page

#### 5. **Hidden Click Trigger: Click Logo 5x = Login**
```javascript
const footerLogoImg = document.querySelector(".footer-logo img");
if (footerLogoImg) {
    let clickCount = 0;
    let lastClickTime = 0;
    
    footerLogoImg.addEventListener("click", function (e) {
        e.stopPropagation();
        
        const currentTime = new Date().getTime();
        
        // Reset if click interval > 800ms
        if (currentTime - lastClickTime < 800) {
            clickCount++;
        } else {
            clickCount = 1;
        }
        lastClickTime = currentTime;
        
        // On 5th click within 800ms intervals → redirect
        if (clickCount === 5) {
            clickCount = 0;
            window.location.href = "/iclabs-login";
        }
    }, true);
}
```
**Easter Egg**: Click footer logo 5x cepat → ke login

#### 6. **Global Click Handler: Close Menu Outside**
```javascript
document.addEventListener("click", function (e) {
    if (!e.target.closest(".navbar")) {
        // Close main menu
        if (navLinks && navLinks.classList.contains("active")) {
            navLinks.classList.remove("active");
            if (menuToggle) menuToggle.classList.remove("active");
        }
        
        // Close all dropdowns
        document.querySelectorAll(".dropdown-content").forEach((content) => {
            content.classList.remove("show");
        });
    }
});
```
**UX**: Click di luar navbar → semua menu tutup (better UX)

### **Code Quality**

✅ **DRY**: Centralized global utilities  
✅ **Event Delegation**: Use .closest() untuk better performance  
✅ **Responsive**: breakpoint 992px untuk mobile/desktop  
✅ **Accessibility**: preventDefault() untuk form handling  
✅ **Error Safe**: null checks sebelum manipulate DOM  

---

## 📊 RINGKASAN FLOW: Request → Response

```
1. USER INTERACTION (Frontend)
   Browser sends request: GET /alumni/5
                    ↓
2. ROUTING (Router.php)
   Parse URL → Match dengan route
   /alumni/5 → AlumniController@detail({id: 5})
                    ↓
3. MIDDLEWARE (optional)
   If /admin route → Check AuthMiddleware
                    ↓
4. CONTROLLER (AlumniController.php)
   detail($params) {
       $data = $model->getById($params['id'])
       $this->view('alumni/detail', $data)
   }
                    ↓
5. MODEL (AlumniModel.php)
   getById($id) → Query database
   SELECT * FROM alumni WHERE id = 5
                    ↓
6. DATABASE (MySQL)
   Jalankan query → Return result
                    ↓
7. VIEW (alumni/detail.php)
   Extract data → Render HTML
   include header.php
   include detail.php (dengan $alumni data)
   include footer.php
                    ↓
8. RESPONSE (Browser)
   HTML dikirim ke browser
   Browser render halaman
```

---

## 💡 KEY TAKEAWAYS UNTUK PRESENTASI

### **Highlights Teknis**
1. **Custom MVC**: Dibangun from scratch, bukan framework
2. **Clean Architecture**: Separation of concerns (Router→Controller→Model→DB)
3. **Security**: Bcrypt, prepared statements, session-based auth
4. **Responsive Frontend**: Mobile-first design dengan vanilla JS
5. **Scalable Structure**: Mudah menambah controller/model baru

### **Keunggulan Design Pattern**
- ✅ Base Controller + Base Model → DRY code
- ✅ Router → Clean URL mapping
- ✅ Middleware → Protect routes
- ✅ View with layout → Template reusability

### **Demo Ideas**
1. **Show Router**: Buka Router.php, jelaskan route mapping
2. **Show Controller**: Jalankan flow request → response (debug)
3. **Show Model**: Tunjukkan CRUD operations & prepared statement
4. **Show Frontend**: Mobile responsiveness & JS interactions
5. **Show Database**: Query jadwal dengan join 3 tabel

---

**Good luck with presentation! 🚀**
