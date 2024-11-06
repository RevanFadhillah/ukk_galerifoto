<?php
session_start();
include '../config/koneksi.php';

// Pastikan pengguna sudah login
if ($_SESSION['status'] != 'login') {
    echo "<script>alert('Anda Belum Login'); location.href='../home.php';</script>";
    exit;
}

// Ambil id komentar yang akan dihapus dan id foto terkait
$id_komentar = $_GET['id_komentar'];
$id_foto = $_GET['id_foto'];
$id_user = $_SESSION['id_user'];  // ID pengguna yang sedang login

// Cek apakah komentar dengan id_komentar ada dan milik pengguna yang tepat
$cek_komentar = mysqli_query($koneksi, "SELECT * FROM komentar_foto WHERE id_komentar='$id_komentar'");
$komentar_data = mysqli_fetch_array($cek_komentar);

// Jika komentar ditemukan, lanjutkan pengecekan hak akses
if ($komentar_data) {
    // Cek apakah yang login adalah pemilik foto atau pemilik komentar
    $foto_owner = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_user FROM foto WHERE id_foto = '$id_foto'"));
    
    if ($foto_owner['id_user'] == $_SESSION['id_user'] || $komentar_data['id_user'] == $_SESSION['id_user']) {
        // Hapus komentar
        $hapus_komentar = mysqli_query($koneksi, "DELETE FROM komentar_foto WHERE id_komentar='$id_komentar'");
        
        if ($hapus_komentar) {
            echo "<script>alert('Komentar berhasil dihapus'); location.href='../admin/home.php?id_album=$id_foto';</script>";
        } else {
            echo "<script>alert('Gagal menghapus komentar'); location.href='../admin/home.php?id_album=$id_foto';</script>";
        }
    } else {
        // Jika pengguna tidak memiliki hak untuk menghapus komentar
        echo "<script>alert('Anda tidak memiliki hak untuk menghapus komentar ini'); location.href='../admin/home.php';</script>";
    }
} else {
    echo "<script>alert('Komentar tidak ditemukan'); location.href='../admin/home.php';</script>";
}
?>
