<?php
include 'koneksi.php';

// Ambil data dari form
$username = $_POST['username'];
$password = md5($_POST['password']); // Gunakan md5, tapi disarankan gunakan password_hash() untuk keamanan
$email = $_POST['email']; // Perbaiki typo dari '$emai' menjadi '$email'
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];

// Pastikan data tidak kosong
if (empty($username) || empty($password) || empty($email) || empty($nama) || empty($alamat)) {
    echo "<script>
        alert('Semua data harus diisi!');
        location.href='../register.php';
    </script>";
    exit();
}

// Menyusun query INSERT dengan menyebutkan kolom-kolom secara eksplisit
$sql = "INSERT INTO user (username, password, email, nama, alamat) 
        VALUES ('$username', '$password', '$email', '$nama', '$alamat')";

// Eksekusi query dan cek apakah berhasil
if (mysqli_query($koneksi, $sql)) {
    echo "<script>
        alert('Pendaftaran akun berhasil!');
        location.href='../login.php'; // Redirect ke halaman login
    </script>";
} else {
    // Jika gagal, tampilkan pesan error
    echo "<script>
        alert('Pendaftaran akun gagal. Silakan coba lagi.');
        location.href='../register.php'; // Redirect ke halaman register
    </script>";
}
?>
