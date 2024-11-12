<?php
session_start();
include '../config/koneksi.php';

// Memastikan admin sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Anda belum login atau bukan admin');
            location.href='../login.php'; // Redirect ke halaman login
          </script>";
    exit();
}

// Mengambil ID user dari URL
$id_user = $_GET['id_user'];

// Validasi apakah id_user ada
if (!isset($id_user) || empty($id_user)) {
    echo "<script>
            alert('ID pengguna tidak valid!');
            location.href='admin_pengguna.php'; // Redirect ke halaman admin_pengguna
          </script>";
    exit();
}

// Menghapus pengguna dari database
$query_hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id_user = '$id_user'");

if ($query_hapus) {
    echo "<script>
            alert('Pengguna berhasil dihapus');
            location.href='admin_pengguna.php'; // Redirect ke halaman admin_pengguna
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus pengguna');
            location.href='admin_pengguna.php'; // Redirect ke halaman admin_pengguna
          </script>";
}
?>
