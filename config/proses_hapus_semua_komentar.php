<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Cek apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Anda harus login sebagai admin');
            location.href='../login.php';
          </script>";
    exit();
}

// Ambil ID foto yang akan dihapus komentarnya
if (isset($_POST['id_foto'])) {
    $id_foto = $_POST['id_foto'];

    // Query untuk menghapus semua komentar pada foto tersebut
    $query = "DELETE FROM komentar_foto WHERE id_foto = '$id_foto'";

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Semua komentar pada foto berhasil dihapus');
                location.href='../admin/admin_foto.php'; // Redirect ke halaman admin foto
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus komentar');
                location.href='../admin/admin_foto.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID foto tidak ditemukan');
            location.href='../admin/admin_foto.php';
          </script>";
}
?>
