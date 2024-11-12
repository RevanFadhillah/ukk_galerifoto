<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
            alert('Anda harus login terlebih dahulu!');
            location.href='../login.php'; // Arahkan ke halaman login
          </script>";
    exit();
}

// Cek apakah form komentar sudah disubmit
if (isset($_POST['kirim_komentar'])) {
    // Ambil data dari form
    $id_foto = $_POST['id_foto'];
    $isi_komentar = mysqli_real_escape_string($koneksi, $_POST['isi_komentar']);
    $id_user = $_SESSION['id_user']; // Ambil id_user dari session
    $tanggal_komentar = date('Y-m-d'); // Tanggal komentar saat ini

    // Query untuk menyimpan komentar ke dalam database
    $query = "INSERT INTO komentar_foto (id_foto, id_user, isi_komentar, tanggal_komentar) 
              VALUES ('$id_foto', '$id_user', '$isi_komentar', '$tanggal_komentar')";

    // Eksekusi query untuk menyimpan komentar
    if (mysqli_query($koneksi, $query)) {
        // Periksa role user
        $query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
        $data_user = mysqli_fetch_assoc($query_user);

        if ($data_user['role'] == 'admin') {
            // Jika admin, redirect ke halaman admin
            echo "<script>
                    alert('Komentar berhasil dikirim');
                    location.href='../admin/admin_index.php'; // Halaman Admin
                  </script>";
        } else {
            // Jika user biasa, redirect ke halaman user
            echo "<script>
                    alert('Komentar berhasil dikirim');
                    location.href='../admin/index.php'; // Halaman User
                  </script>";
        }
    } else {
        // Jika gagal menyimpan komentar
        echo "<script>
                alert('Gagal mengirim komentar!');
                location.href='../index.php'; // Arahkan kembali ke halaman sebelumnya
              </script>";
    }
} else {
    // Jika form tidak disubmit, redirect ke halaman index admin
    header("Location: ../index.php"); // Redirect ke halaman index user
    exit();
}
?>
