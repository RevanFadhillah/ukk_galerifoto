<?php
include 'koneksi.php'; // Koneksi ke database

if (isset($_POST['edit_user'])) {
    // Ambil data dari form
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password']; // Ambil password baru
    $confirm_password = $_POST['confirm_password']; // Ambil konfirmasi password

    // Memeriksa apakah password diubah
    if (!empty($password)) {
        // Verifikasi bahwa password dan konfirmasi password cocok
        if ($password != $confirm_password) {
            echo "<script>alert('Password dan konfirmasi password tidak cocok!'); window.history.back();</script>";
            exit;
        }

        // Enkripsi password baru dengan MD5
        $password = md5($password);

        // Update data user dengan password baru dan username juga diperbarui
        $query = "UPDATE user SET username='$username', nama='$nama', email='$email', password='$password' WHERE id_user='$id_user'";
    } else {
        // Jika password tidak diubah, hanya update username, nama, dan email
        $query = "UPDATE user SET username='$username', nama='$nama', email='$email' WHERE id_user='$id_user'";
    }

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data pengguna berhasil diperbarui!'); window.location.href='../admin/admin_pengguna.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan dalam memperbarui data pengguna.'); window.history.back();</script>";
    }
}
?>
