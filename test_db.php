<?php
$host = '127.0.0.1';
$db   = 'verifikasi_produk';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✅ KONEKSI BERHASIL!\n";
    echo "Database: $db\n";
    
    // Cek tabel
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\nTabel yang ada:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
} catch (PDOException $e) {
    echo "❌ KONEKSI GAGAL!\n";
    echo "Error: " . $e->getMessage() . "\n";
}
?>