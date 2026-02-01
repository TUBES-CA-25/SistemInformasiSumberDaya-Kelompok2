<?php

/**
 * FasilitasModel - Model untuk data Fasilitas Laboratorium
 * 
 * Menangani:
 * - Fetch semua laboratorium
 * - Fetch laboratorium berdasarkan ID
 * - Insert, update, delete laboratorium (inherit dari Model)
 * 
 * Database Table: laboratorium
 * - Primary Key: idLaboratorium
 * - Key Fields: nama, tipe, status, deskripsi, lokasi
 * 
 * Methods:
 * - getAll(): Fetch semua laboratorium
 * - getById(id): Fetch laboratorium by ID
 * - insert/update/delete: Inherit dari parent Model.php
 */

require_once __DIR__ . '/Model.php';

class FasilitasModel extends Model {
    // =========================================================================
    // PROPERTI
    // =========================================================================
    
    /** @var string Nama tabel di database */
    protected $table = 'laboratorium';

    
    // =========================================================================
    // PUBLIC METHODS - READ
    // =========================================================================
    
    /**
     * Get All - Ambil semua data laboratorium
     * 
     * Mengambil seluruh record dari tabel laboratorium.
     * Hasil diurutkan berdasarkan idLaboratorium ascending.
     * 
     * Method: MySQLi Query (tidak Prepared Statement)
     * Return: Array of associative arrays atau [] jika kosong
     * 
     * Example:
     * $labs = $model->getAll();
     * foreach ($labs as $lab) {
     *   echo $lab['nama']; // Akses field laboratorium
     * }
     * 
     * @return array Array berisi semua data laboratorium
     */
    public function getAll() {
        // LANGKAH 1: Prepare query SQL
        $query = "SELECT * FROM " . $this->table . " ORDER BY idLaboratorium ASC";
        
        // LANGKAH 2: Execute query menggunakan MySQLi
        $result = $this->db->query($query);
        
        // LANGKAH 3: Return hasil sebagai array asosiatif
        if ($result) {
            // fetch_all(MYSQLI_ASSOC) menghasilkan Array Asosiatif
            // Format: [['id' => 1, 'nama' => '...'], ['id' => 2, ...]]
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    /**
     * Get By ID - Ambil laboratorium berdasarkan ID
     * 
     * Mengambil satu record laboratorium dengan prepared statement (secure).
     * Primary key default adalah idLaboratorium.
     * 
     * Method: MySQLi Prepared Statement (secure against SQL injection)
     * Return: Single associative array atau null jika tidak ditemukan
     * 
     * Parameter:
     * - $id: Nilai ID yang dicari (integer)
     * - $idColumn: Nama kolom untuk filter (default: 'idLaboratorium')
     * 
     * Example:
     * $lab = $model->getById(1);  // Get laboratorium dengan idLaboratorium = 1
     * 
     * @param int $id ID laboratorium
     * @param string $idColumn Nama kolom untuk filter (default: idLaboratorium)
     * @return array|null Data laboratorium atau null jika tidak ada
     */
    public function getById($id, $idColumn = 'idLaboratorium') {
        // LANGKAH 1: Prepare query dengan placeholder (?)
        $query = "SELECT * FROM " . $this->table . " WHERE $idColumn = ?";
        $stmt = $this->db->prepare($query);
        
        // LANGKAH 2: Validate prepared statement
        if ($stmt) {
            // LANGKAH 3: Bind parameter dengan tipe integer ("i")
            // "i" = integer, "s" = string, "d" = double, "b" = blob
            $stmt->bind_param("i", $id);
            
            // LANGKAH 4: Execute statement
            $stmt->execute();
            
            // LANGKAH 5: Get result dan fetch single row
            $result = $stmt->get_result();
            return $result->fetch_assoc();  // Return single array atau null
        }
        
        return null;
    }

    
    // =========================================================================
    // NOTE: INSERT, UPDATE, DELETE
    // =========================================================================
    
    /**
     * Methods insert(), update(), delete() diwarisi dari parent Model.php
     * 
     * Penggunaan:
     * - insert($data): Masukkan data baru (return boolean)
     * - update($id, $data): Update data by ID (return boolean)
     * - delete($id): Hapus data by ID (return boolean)
     * - getLastInsertId(): Ambil ID terakhir yang di-insert
     */
}