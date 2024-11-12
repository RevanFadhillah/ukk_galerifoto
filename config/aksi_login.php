<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Ambil data dari form login
$username = $_POST['username'];
$password = md5($_POST['password']); // Password yang di-hash (disarankan menggunakan password_hash() di database)

// Query untuk mendapatkan data pengguna berdasarkan username dan password
$sql = "SELECT * FROM user WHERE username = ? AND password = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Cek jika pengguna ditemukan
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Set session berdasarkan data yang ditemukan
    $_SESSION['username'] = $data['username'];
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['role'] = $data['role']; // Menyimpan role

    // Cek role untuk redirect
    if ($data['role'] == 'admin') {
        // Jika admin, redirect ke halaman admin
        echo "<script>
                alert('Login Berhasil Selamat Datang Admin');
                location.href='../admin/admin_index.php'; // Halaman Admin
              </script>";
    } else {
        // Jika user biasa, redirect ke dashboard user
        echo "<script>
                alert('Login Berhasil Selamat Datang User');
                location.href='../admin/index.php'; // Halaman User
              </script>";
    }
} else {
    // Jika username atau password salah
    echo "<script>
            alert('Username atau Password salah');
            location.href='../login.php';
          </script>";
}
?>
