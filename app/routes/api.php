<?php
/**
 * API Routes
 * Define semua routes untuk API
 */

/**
 * Peraturan Lab Routes
 * GET    /api/peraturan-lab              - Get all peraturan lab
 * GET    /api/peraturan-lab/{id}         - Get peraturan lab by ID
 * POST   /api/peraturan-lab              - Create peraturan lab
 * POST   /api/peraturan-lab/{id}         - Update peraturan lab (dengan FormData)
 * DELETE /api/peraturan-lab/{id}         - Delete peraturan lab
 */
$router->get('/peraturan-lab', 'PeraturanLabController', 'index');
$router->get('/peraturan-lab/{id}', 'PeraturanLabController', 'show');
$router->post('/peraturan-lab', 'PeraturanLabController', 'store');
$router->post('/peraturan-lab/{id}', 'PeraturanLabController', 'update'); // Accept POST for FormData update
$router->put('/peraturan-lab/{id}', 'PeraturanLabController', 'update');
$router->delete('/peraturan-lab/{id}', 'PeraturanLabController', 'delete');

/**
 * Sanksi Lab Routes
 * GET    /api/sanksi-lab                 - Get all sanksi lab
 * GET    /api/sanksi-lab/{id}            - Get sanksi lab by ID
 * POST   /api/sanksi-lab                 - Create sanksi lab
 * POST   /api/sanksi-lab/{id}            - Update sanksi lab (dengan FormData)
 * DELETE /api/sanksi-lab/{id}            - Delete sanksi lab
 */
$router->get('/sanksi-lab', 'SanksiLabController', 'apiIndex');
$router->get('/sanksi-lab/{id}', 'SanksiLabController', 'apiShow');
$router->post('/sanksi-lab', 'SanksiLabController', 'store');
$router->post('/sanksi-lab/{id}', 'SanksiLabController', 'update'); // Accept POST for FormData update
$router->put('/sanksi-lab/{id}', 'SanksiLabController', 'update');
$router->delete('/sanksi-lab/{id}', 'SanksiLabController', 'delete');

/**
 * Laboratorium Routes
 * GET    /api/laboratorium              - Get all laboratorium
 * GET    /api/laboratorium/{id}         - Get laboratorium by ID
 * POST   /api/laboratorium              - Create laboratorium
 * PUT    /api/laboratorium/{id}         - Update laboratorium
 * DELETE /api/laboratorium/{id}         - Delete laboratorium
 */
$router->get('/laboratorium', 'LaboratoriumController', 'index');
$router->get('/laboratorium/{id}', 'LaboratoriumController', 'show');
$router->post('/laboratorium', 'LaboratoriumController', 'store');
$router->put('/laboratorium/{id}', 'LaboratoriumController', 'update');
$router->delete('/laboratorium/{id}', 'LaboratoriumController', 'delete');

/**
 * Asisten Routes
 * GET    /api/asisten                   - Get all asisten
 * GET    /api/asisten/{id}              - Get asisten by ID
 * GET    /api/asisten/{id}/matakuliah   - Get matakuliah by asisten
 * POST   /api/asisten                   - Create asisten
 * PUT    /api/asisten/{id}              - Update asisten
 * DELETE /api/asisten/{id}              - Delete asisten
 */
$router->get('/asisten', 'AsistenController', 'index');
$router->get('/asisten/{id}', 'AsistenController', 'show');
$router->get('/asisten/{id}/matakuliah', 'AsistenController', 'matakuliah');
$router->post('/asisten', 'AsistenController', 'store');
$router->put('/asisten/{id}', 'AsistenController', 'update');
$router->delete('/asisten/{id}', 'AsistenController', 'delete');

/**
 * Matakuliah Routes
 * GET    /api/matakuliah                - Get all matakuliah
 * GET    /api/matakuliah/{id}           - Get matakuliah by ID
 * GET    /api/matakuliah/{id}/asisten   - Get asisten by matakuliah
 * POST   /api/matakuliah                - Create matakuliah
 * PUT    /api/matakuliah/{id}           - Update matakuliah
 * DELETE /api/matakuliah/{id}           - Delete matakuliah
 */
$router->get('/matakuliah', 'MatakuliahController', 'index');
$router->get('/matakuliah/{id}', 'MatakuliahController', 'show');
$router->get('/matakuliah/{id}/asisten', 'MatakuliahController', 'asisten');
$router->post('/matakuliah', 'MatakuliahController', 'store');
$router->put('/matakuliah/{id}', 'MatakuliahController', 'update');
$router->delete('/matakuliah/{id}', 'MatakuliahController', 'delete');

/**
 * Jadwal Praktikum Routes
 * GET    /api/jadwal                    - Get all jadwal
 * GET    /api/jadwal/{id}               - Get jadwal by ID
 * POST   /api/jadwal                    - Create jadwal
 * PUT    /api/jadwal/{id}               - Update jadwal
 * DELETE /api/jadwal/{id}               - Delete jadwal
 */
$router->get('/jadwal', 'JadwalPraktikumController', 'index');
$router->get('/jadwal/{id}', 'JadwalPraktikumController', 'show');
$router->post('/jadwal', 'JadwalPraktikumController', 'store');
$router->post('/jadwal/delete-multiple', 'JadwalPraktikumController', 'deleteMultiple');
$router->put('/jadwal/{id}', 'JadwalPraktikumController', 'update');
$router->delete('/jadwal/{id}', 'JadwalPraktikumController', 'delete');

/**
 * Informasi Lab Routes
 * GET    /api/informasi                 - Get all informasi
 * GET    /api/informasi/{id}            - Get informasi by ID
 * GET    /api/informasi/tipe/{type}     - Get informasi by type
 * POST   /api/informasi                 - Create informasi
 * PUT    /api/informasi/{id}            - Update informasi
 * DELETE /api/informasi/{id}            - Delete informasi
 */
$router->get('/informasi', 'InformasiLabController', 'index');
$router->get('/informasi/{id}', 'InformasiLabController', 'show');
$router->get('/informasi/tipe/{type}', 'InformasiLabController', 'byType');
$router->post('/informasi', 'InformasiLabController', 'store');
$router->put('/informasi/{id}', 'InformasiLabController', 'update');
$router->delete('/informasi/{id}', 'InformasiLabController', 'delete');

/**
 * Visi Misi Routes
 * GET    /api/visi-misi                 - Get latest visi misi
 * POST   /api/visi-misi                 - Create visi misi
 */
$router->get('/visi-misi', 'VisMisiController', 'getLatest');
$router->post('/visi-misi', 'VisMisiController', 'store');

/**
 * Manajemen Routes
 * GET    /api/manajemen                 - Get all manajemen
 * GET    /api/manajemen/{id}            - Get manajemen by ID
 * POST   /api/manajemen                 - Create manajemen
 * PUT    /api/manajemen/{id}            - Update manajemen
 * DELETE /api/manajemen/{id}            - Delete manajemen
 */
$router->get('/manajemen', 'ManajemenController', 'index');
$router->get('/manajemen/{id}', 'ManajemenController', 'show');
$router->post('/manajemen', 'ManajemenController', 'store');
$router->put('/manajemen/{id}', 'ManajemenController', 'update');
$router->delete('/manajemen/{id}', 'ManajemenController', 'delete');

/**
 * Kontak Routes
 * GET    /api/kontak                    - Get latest kontak
 * POST   /api/kontak                    - Create kontak
 * PUT    /api/kontak/{id}               - Update kontak
 */
$router->get('/kontak', 'KontakController', 'getLatest');
$router->post('/kontak', 'KontakController', 'store');
$router->put('/kontak/{id}', 'KontakController', 'update');

/**
 * Format Penulisan Routes
 */
$router->get('/formatpenulisan', 'FormatPenulisanController', 'apiIndex');
$router->get('/formatpenulisan/{id}', 'FormatPenulisanController', 'apiShow');
$router->post('/formatpenulisan', 'FormatPenulisanController', 'store');
$router->put('/formatpenulisan/{id}', 'FormatPenulisanController', 'update');
$router->delete('/formatpenulisan/{id}', 'FormatPenulisanController', 'delete');

// Health check endpoint
$router->get('/health', 'HealthController', 'check');
?>
