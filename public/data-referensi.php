<?php
/**
 * Halaman untuk melihat data mata kuliah dan laboratorium yang tersedia
 */

require_once '../app/config/config.php';
require_once '../app/config/Database.php';
require_once '../app/models/Model.php';
require_once '../app/models/MatakuliahModel.php';
require_once '../app/models/LaboratoriumModel.php';

$matakuliahModel = new MatakuliahModel();
$laboratoriumModel = new LaboratoriumModel();

$matakuliahs = $matakuliahModel->getAll();
$laboratoriums = $laboratoriumModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Referensi Upload Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .copy-btn {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .copy-btn:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>📋 Data Referensi untuk Upload Jadwal</h1>
        
        <div class="alert alert-info">
            <h5>💡 Petunjuk:</h5>
            <p>Gunakan nama-nama yang tercantum di bawah ini dalam file CSV Anda. 
            Klik pada nama untuk menyalin ke clipboard.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>📚 Mata Kuliah Tersedia (<?php echo count($matakuliahs); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Mata Kuliah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($matakuliahs)): ?>
                                    <tr><td colspan="2" class="text-center">Tidak ada data mata kuliah</td></tr>
                                    <?php else: ?>
                                    <?php foreach ($matakuliahs as $mk): ?>
                                    <tr>
                                        <td>
                                            <code class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($mk['kodeMatakuliah'] ?? ''); ?>')">
                                                <?php echo htmlspecialchars($mk['kodeMatakuliah'] ?? '-'); ?>
                                            </code>
                                        </td>
                                        <td>
                                            <span class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($mk['namaMatakuliah']); ?>')">
                                                <?php echo htmlspecialchars($mk['namaMatakuliah']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🏢 Laboratorium Tersedia (<?php echo count($laboratoriums); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Laboratorium</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($laboratoriums)): ?>
                                    <tr><td colspan="2" class="text-center">Tidak ada data laboratorium</td></tr>
                                    <?php else: ?>
                                    <?php foreach ($laboratoriums as $lab): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($lab['idLaboratorium']); ?></td>
                                        <td>
                                            <span class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($lab['nama']); ?>')">
                                                <?php echo htmlspecialchars($lab['nama']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>📝 Contoh CSV yang Benar</h5>
                    </div>
                    <div class="card-body">
                        <p>Berikut contoh data CSV dengan nama yang sesuai database Anda:</p>
                        <pre class="bg-light p-3" style="font-size: 12px;">
Mata Kuliah,Laboratorium,Hari,Waktu Mulai,Waktu Selesai,Kelas,Status
<?php 
if (!empty($matakuliahs) && !empty($laboratoriums)):
    $sampleCount = 0;
    foreach ($matakuliahs as $mk): 
        if ($sampleCount >= 5) break;
        $labName = $laboratoriums[$sampleCount % count($laboratoriums)]['nama'] ?? 'Lab Komputer 1';
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $times = [['08:00', '10:00'], ['10:00', '12:00'], ['13:00', '15:00'], ['15:00', '17:00']];
        $classes = ['A', 'B', 'C'];
        
        $day = $days[$sampleCount % count($days)];
        $time = $times[$sampleCount % count($times)];
        $class = $classes[$sampleCount % count($classes)];
        
        echo htmlspecialchars($mk['namaMatakuliah']) . ',' . 
             htmlspecialchars($labName) . ',' . 
             $day . ',' . 
             $time[0] . ',' . 
             $time[1] . ',' . 
             $class . ',Aktif' . "\n";
        $sampleCount++;
    endforeach;
else:
    echo "Microcontroller,Lab Komputer 1,Senin,08:00,10:00,A,Aktif\n";
    echo "Struktur Data,Lab Komputer 2,Selasa,10:00,12:00,B,Aktif\n";
endif;
?>
                        </pre>
                        
                        <div class="mt-3">
                            <a href="api.php/jadwal-praktikum/csv-template" class="btn btn-success">
                                📥 Download Template CSV (Updated)
                            </a>
                            <a href="upload-jadwal-csv.php" class="btn btn-primary">
                                📤 Upload CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h5>🔗 Navigasi</h5>
                </div>
                <div class="card-body">
                    <a href="upload-jadwal-csv.php" class="btn btn-primary me-2">📤 Upload CSV</a>
                    <a href="admin-jadwal-upload.php" class="btn btn-secondary me-2">📄 Upload Excel</a>
                    <a href="fix-zip-extension.php" class="btn btn-warning me-2">🔧 Fix ZIP Extension</a>
                    <a href="simple-upload-test.php" class="btn btn-info">🧪 Test Upload</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show temporary feedback
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `✅ Disalin: "${text}"`;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 2000);
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>