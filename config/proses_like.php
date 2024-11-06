<?php 
session_start();
include 'koneksi.php';
$id_foto = $_GET['id_foto'];
$id_user = $_SESSION['id_user'];

$cek_suka = mysqli_query($koneksi, "SELECT * FROM like_foto WHERE id_foto='$id_foto' AND id_user='$id_user'");

if (mysqli_num_rows($cek_suka) == 1) {
while($row = mysqli_fetch_array($cek_suka)){
    $id_like = $row['id_like'];
    $query = mysqli_query($koneksi, "DELETE FROM like_foto WHERE id_like='$id_like'");
    echo "<script>
    location.href='../admin/index.php';
    </script>";
    }
}else {
    $tanggal_like = date('Y-m-d');
$query = mysqli_query($koneksi, "INSERT INTO like_foto VALUES('','$id_foto','$id_user','$tanggal_like')");

echo "<script>
location.href='../admin/index.php';
</script>";
}


?>