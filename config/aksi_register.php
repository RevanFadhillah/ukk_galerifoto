<?php
include 'koneksi.php';
$username = $_POST['username'];
$password = md5($_POST['password']);
$emai = $_POST['email'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];

$sql = mysqli_query($koneksi, "INSERT INTO user VALUES ('','$username','$password','$emai','$nama','$alamat')");


if ($sql) {
    echo "<script>
    alert('pendaftaran akun berhasil');
    location.href='../login.php'; 
    </script>";
}

?>