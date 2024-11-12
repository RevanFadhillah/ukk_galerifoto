<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Anda harus login sebagai admin');
            location.href='../login.php';
          </script>";
    exit();
}

if (isset($_POST['tambah'])) {
$judul_foto = $_POST['judul_foto'];
$deskripsi_foto = $_POST['deskripsi_foto'];
$tanggal_unggah = date('Y-m-d');
$id_album = $_POST['id_album'];
$id_user = $_SESSION['id_user'];
$foto = $_FILES['lokasi_file']['name'];
$tmp = $_FILES['lokasi_file']['tmp_name'];
$lokasi = '../assets/img/';
$nama_foto = rand().'-'.$foto;

move_uploaded_file($tmp, $lokasi.$nama_foto);

$sql = mysqli_query($koneksi, "INSERT INTO foto VALUES('','$judul_foto','$deskripsi_foto','$tanggal_unggah','$nama_foto','$id_album','$id_user')");

echo "<script>
alert('Data Berhasil Disimpam');
location.href='../admin/admin_foto.php';
</script>"; 
}

if (isset($_POST['edit'])) {
$id_foto = $_POST['id_foto'];
$judul_foto = $_POST['judul_foto'];
$deskripsi_foto = $_POST['deskripsi_foto'];
$tanggal_unggah   = date ('Y-m-d');
$id_album = $_POST['id_album'];
$id_user = $_SESSION['id_user'];
$foto = $_FILES['lokasi_file']['name'];
$tmp = $_FILES['lokasi_file']['tmp_name'];
$lokasi = '../assets/img/';
$nama_foto = rand().'-'.$foto;

if ($foto == null) {
    $sql = mysqli_query($koneksi, "UPDATE foto SET judul_foto='$judul_foto',deskripsi_foto='$deskripsi_foto',tanggal_unggah='$tanggal_unggah', id_album='$id_album' WHERE id_foto='$id_foto'");
    }else {
        $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE id_foto='$id_foto'");
        $data = mysqli_fetch_array($query);
        if (is_file('../assets/img/'.$data['lokasi_file'])) {
            unlink('../assets/img/'.$data['lokasi_file']);
        }
        move_uploaded_file($tmp, $lokasi.$nama_foto);
        $sql = mysqli_query($koneksi, "UPDATE foto SET judul_foto='$judul_foto',deskripsi_foto='$deskripsi_foto',tanggal_unggah='$tanggal_unggah',lokasi_file='$nama_foto', id_album='$id_album' WHERE id_foto='$id_foto'");
    }
    echo "<script>
alert('Data Berhasil Diperbarui');
location.href='../admin/admin_foto.php';
</script>"; 
}

if (isset($_POST['hapus'])) {
    $id_foto = $_POST['id_foto'];
    $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE id_foto='$id_foto'");
        $data = mysqli_fetch_array($query);
        if (is_file('../assets/img/'.$data['lokasi_file'])) {
            unlink('../assets/img/'.$data['lokasi_file']);
        }

        $sql = mysqli_query($koneksi, "DELETE FROM foto WHERE id_foto='$id_foto'");
        echo "<script>
        alert('Data Berhasil Dihapus');
        location.href='../admin/admin_foto.php';
        </script>"; 
}