<?php
/**
 * Migration script to create master table 'dosen' and link it to
 * 'jadwalpraktikum' and 'jadwalupk'.
 */

require_once __DIR__ . '/app/config/Database.php';

try {
    $database = new Database();
    $mysqli = $database->connect();

    echo "Starting migration...\n";

    // 1. Create table 'dosen'
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS dosen (
            idDosen INT(11) AUTO_INCREMENT PRIMARY KEY,
            nip VARCHAR(50) UNIQUE NULL,
            nama VARCHAR(255) NOT NULL,
            email VARCHAR(100) NULL,
            status VARCHAR(20) DEFAULT 'Aktif'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    if ($mysqli->query($createTableQuery)) {
        echo "Table 'dosen' successfully created or already exists.\n";
    } else {
        throw new Exception("Failed to create 'dosen' table: " . $mysqli->error);
    }

    // 2. Extract existing lecturer names from 'jadwalpraktikum' and 'jadwalupk'
    $lecturers = [];

    // Check if column 'dosen' exists in 'jadwalpraktikum'
    $checkJpDosen = $mysqli->query("SHOW COLUMNS FROM jadwalpraktikum LIKE 'dosen'");
    if ($checkJpDosen && $checkJpDosen->num_rows > 0) {
        $jpRes = $mysqli->query("SELECT DISTINCT dosen FROM jadwalpraktikum WHERE dosen IS NOT NULL AND TRIM(dosen) != ''");
        if ($jpRes) {
            while ($row = $jpRes->fetch_assoc()) {
                $name = trim($row['dosen']);
                if (!in_array($name, $lecturers)) {
                    $lecturers[] = $name;
                }
            }
        }
    }

    // Check if column 'dosen' exists in 'jadwalupk'
    $checkUpkDosen = $mysqli->query("SHOW COLUMNS FROM jadwalupk LIKE 'dosen'");
    if ($checkUpkDosen && $checkUpkDosen->num_rows > 0) {
        $upkRes = $mysqli->query("SELECT DISTINCT dosen FROM jadwalupk WHERE dosen IS NOT NULL AND TRIM(dosen) != ''");
        if ($upkRes) {
            while ($row = $upkRes->fetch_assoc()) {
                $name = trim($row['dosen']);
                if (!in_array($name, $lecturers)) {
                    $lecturers[] = $name;
                }
            }
        }
    }

    // 3. Insert unique lecturers into 'dosen' table (if they don't exist yet)
    foreach ($lecturers as $name) {
        $stmt = $mysqli->prepare("SELECT idDosen FROM dosen WHERE LOWER(TRIM(nama)) = LOWER(TRIM(?))");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
            $insertStmt = $mysqli->prepare("INSERT INTO dosen (nama) VALUES (?)");
            $insertStmt->bind_param("s", $name);
            $insertStmt->execute();
            echo "Inserted lecturer: $name\n";
        }
    }

    // 4. Update 'jadwalpraktikum' to use idDosen
    $checkJpIdDosen = $mysqli->query("SHOW COLUMNS FROM jadwalpraktikum LIKE 'idDosen'");
    if ($checkJpIdDosen->num_rows == 0) {
        if ($mysqli->query("ALTER TABLE jadwalpraktikum ADD COLUMN idDosen INT(11) NULL AFTER dosen")) {
            echo "Added column 'idDosen' to 'jadwalpraktikum'.\n";
        } else {
            throw new Exception("Failed to add 'idDosen' column to 'jadwalpraktikum': " . $mysqli->error);
        }
    }

    // If string 'dosen' column still exists, update idDosen matching by name
    $checkJpDosen = $mysqli->query("SHOW COLUMNS FROM jadwalpraktikum LIKE 'dosen'");
    if ($checkJpDosen->num_rows > 0) {
        $updateJp = "
            UPDATE jadwalpraktikum j 
            JOIN dosen d ON LOWER(TRIM(j.dosen)) = LOWER(TRIM(d.nama))
            SET j.idDosen = d.idDosen;
        ";
        if ($mysqli->query($updateJp)) {
            echo "Updated 'jadwalpraktikum.idDosen' from original 'dosen' names.\n";
            // Now safe to drop 'dosen' column
            if ($mysqli->query("ALTER TABLE jadwalpraktikum DROP COLUMN dosen")) {
                echo "Dropped string column 'dosen' from 'jadwalpraktikum'.\n";
            }
        }
    }

    // 5. Update 'jadwalupk' to use idDosen
    $checkUpkIdDosen = $mysqli->query("SHOW COLUMNS FROM jadwalupk LIKE 'idDosen'");
    if ($checkUpkIdDosen->num_rows == 0) {
        if ($mysqli->query("ALTER TABLE jadwalupk ADD COLUMN idDosen INT(11) NULL AFTER dosen")) {
            echo "Added column 'idDosen' to 'jadwalupk'.\n";
        } else {
            throw new Exception("Failed to add 'idDosen' column to 'jadwalupk': " . $mysqli->error);
        }
    }

    // If string 'dosen' column still exists, update idDosen matching by name
    $checkUpkDosen = $mysqli->query("SHOW COLUMNS FROM jadwalupk LIKE 'dosen'");
    if ($checkUpkDosen->num_rows > 0) {
        $updateUpk = "
            UPDATE jadwalupk j 
            JOIN dosen d ON LOWER(TRIM(j.dosen)) = LOWER(TRIM(d.nama))
            SET j.idDosen = d.idDosen;
        ";
        if ($mysqli->query($updateUpk)) {
            echo "Updated 'jadwalupk.idDosen' from original 'dosen' names.\n";
            // Now safe to drop 'dosen' column
            if ($mysqli->query("ALTER TABLE jadwalupk DROP COLUMN dosen")) {
                echo "Dropped string column 'dosen' from 'jadwalupk'.\n";
            }
        }
    }

    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
