<?php
session_start();
$id_user = $_SESSION['id_user'];
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
  echo "<script>
    alert ('Anda Belum Login');
    location.href='../index.php';
    </script>";
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
  <link rel="stylesheet" href="assets/css1/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <div class="container">
      <a class="navbar-brand text-white" href="index.php"><i class="fa fa-camera-retro"></i> Website Galeri Foto</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link text-white" href="home.php"><i class="fa fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="album.php"><i class="fa fa-folder-open"></i> Album</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="foto.php"><i class="fa fa-image"></i> Foto</a>
          </li>
        </ul>
        <span class="navbar-text text-white ms-3">
          hallo <?php echo $user_name; ?>
        </span>
        <a href="../config/aksi_logout.php" class="btn btn-outline-danger ml-2 ms-4">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container mt-2">
    <div class="row">
      <?php
      $query = mysqli_query($koneksi, "SELECT * FROM foto INNER JOIN user ON foto.id_user=user.id_user INNER JOIN album ON foto.id_album=album.id_album");
      while ($data = mysqli_fetch_array($query)) {
      ?>
        <div class="col-md-3">
          <a type="button" data-bs-toggle="modal" data-bs-target="#komentar<?php echo $data['id_foto'] ?>">

            <div class="card mb-2">
              <img style="height: 12rem;" src="../assets/img/<?php echo $data['lokasi_file'] ?>" class="card-img-top" title="<?php echo $data['judul_foto'] ?>">
              <div class="card-footer text-center">

                <?php
                $id_foto = $data['id_foto'];
                $cek_suka = mysqli_query($koneksi, "SELECT * FROM like_foto WHERE id_foto='$id_foto' AND id_user='$id_user'");
                if (mysqli_num_rows($cek_suka) == 1) { ?>
                  <a href="../config/proses_like.php?id_foto=<?php echo $data['id_foto'] ?>" type="submit" name="batal_suka"><i class="fa fa-heart"></i></a>

                <?php } else { ?>
                  <a href="../config/proses_like.php?id_foto=<?php echo $data['id_foto'] ?>" type="submit" name="suka"><i class="fa-regular fa-heart"></i></a>

                <?php }
                $like = mysqli_query($koneksi, "SELECT * FROM like_foto WHERE id_foto='$id_foto'");
                echo mysqli_num_rows($like) . ' suka';
                ?>
                <a href="" type="button" data-bs-toggle="modal" data-bs-target="#komentar<?php echo $data['id_foto'] ?>"><i class="fa-regular fa-comment"></i></a>
                <?php
                $jml_komen = mysqli_query($koneksi, "SELECT * FROM komentar_foto WHERE id_foto='$id_foto'");
                echo mysqli_num_rows($jml_komen) . ' komentar';
                ?>

                <a href="../assets/img/<?php echo $data['lokasi_file'] ?>" download class="btn btn-outline-info m-1">
                  <i class="fa fa-download"></i> Download
                </a>
              </div>
            </div>
          </a>

          <div class="modal fade" id="komentar<?php echo $data['id_foto'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-8">
                      <img src="../assets/img/<?php echo $data['lokasi_file'] ?>" class="card-img-top" title="<?php echo $data['judul_foto'] ?>">
                    </div>
                    <div class="col-md-4">
                      <div class="m-2">
                        <div class="overflow-auto">
                          <div class="sticky-top">
                            <strong><?php echo $data['judul_foto'] ?></strong><br>
                            <span class="badge bg-secondary"><?php echo $data['nama'] ?></span>
                            <span class="badge bg-secondary"><?php echo $data['tanggal_unggah'] ?></span>
                            <span class="badge bg-primary"><?php echo $data['nama_album'] ?></span>
                          </div>
                          <hr>
                          <p align="left">
                            <?php echo $data['deskripsi_foto'] ?>
                          </p>
                          <hr>
                          <?php
                          $id_foto = $data['id_foto'];
                          $komentar = mysqli_query($koneksi, "SELECT * FROM komentar_foto INNER JOIN user ON komentar_foto.id_user=user.id_user WHERE komentar_foto.id_foto='$id_foto'");
                          while ($row = mysqli_fetch_array($komentar)) {
                            $is_comment_owner = ($row['id_user'] == $id_user);
                          ?>
                            <p align="left">
                              <strong><?php echo $row['nama'] ?></strong>: <?php echo $row['isi_komentar'] ?>
                              <?php if ($is_comment_owner) { ?>
                                <a href="../config/proses_hapus_komentar.php?id_komentar=<?php echo $row['id_komentar'] ?>&id_foto=<?php echo $id_foto ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</a>
                              <?php } ?>
                            </p>
                          <?php } ?>

                          <hr>
                          <div class="sticky-bottom">
                            <form action="../config/proses_komentar.php" method="POST">
                              <div class="input-group">
                                <input type="hidden" name="id_foto" value="<?php echo $data['id_foto'] ?>">
                                <input type="text" name="isi_komentar" class="form-control" placeholder="Tambah Komentar">
                                <div class="input-group-prepend">
                                  <button type="submit" name="kirim_komentar" class="btn btn-outline-primary">Kirim</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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
</body>

</html>
