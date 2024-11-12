<?php
session_start();
$id_user = $_SESSION['id_user'];
include '../config/koneksi.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Anda belum login atau bukan admin');
            location.href='../login.php'; // Redirect ke halaman login
          </script>";
    exit();
}


// Ambil nama pengguna dari database
$query_user = mysqli_query($koneksi, "SELECT nama FROM user WHERE id_user = '$id_user'");
$user_data = mysqli_fetch_array($query_user);
$user_name = $user_data['nama'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <div class="container">
        <a class="navbar-brand text-white" href="admin_index.php"><i class="fa fa-camera-retro"></i> Website Galeri Foto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="admin_home.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="admin_album.php"><i class="fa fa-folder-open"></i> Album</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="admin_foto.php"><i class="fa fa-image"></i> Foto</a></li>
                <li class="nav-item">
            <a class="nav-link text-white" href="admin_pengguna.php"><i class="fa fa-user"></i> User</a>
          </li>
            </ul>
            <span class="navbar-text text-white ms-3">
          hallo <?php echo $user_name; ?>
        </span>
            <a href="../config/aksi_logout.php" class="btn btn-outline-danger ml-2 ms-4">Keluar</a>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <h3>Album:</h3>
    
    <?php
    $album = mysqli_query($koneksi, "SELECT * FROM album WHERE id_user='$id_user'");
    while ($row = mysqli_fetch_array($album)) { ?>
        <a href="admin_home.php?id_album=<?php echo $row['id_album'] ?>" class="btn btn-outline-primary"><?php echo $row['nama_album'] ?></a>
    <?php } ?>

    <div class="row">
        <?php
        $id_album = isset($_GET['id_album']) ? $_GET['id_album'] : '';
        $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE id_user='$id_user'" . ($id_album ? " AND id_album='$id_album'" : ''));
        
        while ($data = mysqli_fetch_array($query)) {
            $id_foto = $data['id_foto'];
            $like = mysqli_query($koneksi, "SELECT * FROM like_foto WHERE id_foto='$id_foto'");
            $jumlah_like = mysqli_num_rows($like);
            $komentar = mysqli_query($koneksi, "SELECT * FROM komentar_foto WHERE id_foto='$id_foto'");
            $jumlah_komentar = mysqli_num_rows($komentar);
            ?>
            <div class="col-md-3 mt-2">
                <div class="card">
                    <img style="height: 12rem;" src="../assets/img/<?php echo $data['lokasi_file'] ?>" class="card-img-top" title="<?php echo $data['judul_foto'] ?>">
                    <div class="card-footer text-center">
                        <span class="me-3">
                            <a href="../config/proses_like.php?id_foto=<?php echo $id_foto; ?>" class="text-decoration-none">
                                <i class="fa<?php echo (mysqli_num_rows($like) > 0) ? ' fa-heart' : ' fa-regular fa-heart'; ?>"></i>
                            </a>
                            <?php echo $jumlah_like . ' Suka'; ?>
                        </span>
                        <span>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#komentarModal<?php echo $id_foto; ?>" class="text-decoration-none">
                                <i class="fa-regular fa-comment"></i> <?php echo ($jumlah_komentar) . ' Komentar'; ?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>

<div class="modal fade" id="komentarModal<?php echo $id_foto; ?>" tabindex="-1" aria-labelledby="komentarModalLabel<?php echo $id_foto; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="komentarModalLabel<?php echo $id_foto; ?>">Komentar Foto: <?php echo $data['judul_foto']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
    <?php
    // Query untuk mengambil komentar pada foto
    $komentar_foto = mysqli_query($koneksi, "SELECT komentar_foto.*, user.nama AS nama_user FROM komentar_foto INNER JOIN user ON komentar_foto.id_user = user.id_user WHERE komentar_foto.id_foto = '$id_foto'");
    
    // Loop untuk menampilkan komentar
    while ($row = mysqli_fetch_array($komentar_foto)) {
        // Inisialisasi tombol hapus, hanya akan ditampilkan jika yang login adalah pemilik foto atau pemilik komentar
        $hapus_komentar_btn = '';

        // Cek apakah yang login adalah pemilik foto atau pemilik komentar
        $foto_owner = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_user FROM foto WHERE id_foto = '$id_foto'"));
        if ($_SESSION['id_user'] == $foto_owner['id_user'] || $_SESSION['id_user'] == $row['id_user']) {
            // Tampilkan tombol hapus untuk pemilik foto atau pemilik komentar
            $hapus_komentar_btn = "<a href='../config/proses_hapus_komentar.php?id_komentar={$row['id_komentar']}&id_foto=$id_foto' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus komentar ini?\")'>Hapus</a>";
        }

        // Menampilkan komentar beserta tombol hapus
        echo "<p><strong>{$row['nama_user']}:</strong> {$row['isi_komentar']} $hapus_komentar_btn</p>";
    }
    ?>
</div>



            <div class="modal-footer">
                <form action="../config/proses_komentar.php" method="POST" class="w-100">
                    <input type="hidden" name="id_foto" value="<?php echo $id_foto; ?>">
                    <div class="input-group">
                        <input type="text" name="isi_komentar" class="form-control" placeholder="Tambah Komentar" required>
                        <button type="submit" name="kirim_komentar" class="btn btn-outline-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


        <?php } ?>
    </div>
</div>

<footer class="d-flex justify-content-center border-top mt-3" style="background-color: #000000; color: #ffffff; position: fixed; bottom: 0; width: 100%; padding: 10px 0;">
    <p>&copy; UKK PPLG 2024 | Revan Fadhillah Sonjaya</p>
</footer>

<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>