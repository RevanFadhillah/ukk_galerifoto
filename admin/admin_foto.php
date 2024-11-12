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
      <li class="nav-item">
    <a class="nav-link text-white" href="admin_home.php"><i class="fa fa-home"></i> Home</a>
</li>
<li class="nav-item">
    <a class="nav-link text-white" href="admin_album.php"><i class="fa fa-folder-open"></i> Album</a>
</li>
<li class="nav-item">
    <a class="nav-link text-white" href="admin_foto.php"><i class="fa fa-image"></i> Foto</a>
</li>
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


    <div class="container">
        <div class="row">
        <div class="col-md-4">
  <div class="card mt-2">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Tambah Foto</h5>
    </div>
    <div class="card-body">
      <form action="../config/aksi_admin_foto.php" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
          <label for="judul_foto" class="form-label">Judul Foto</label>
          <input type="text" name="judul_foto" id="judul_foto" class="form-control form-control-lg" placeholder="Masukkan Judul Foto" required>
        </div>

        <div class="mb-3">
          <label for="deskripsi_foto" class="form-label">Deskripsi</label>
          <textarea class="form-control form-control-lg" name="deskripsi_foto" id="deskripsi_foto" placeholder="Masukkan Deskripsi Foto" rows="4" required></textarea>
        </div>

        <div class="mb-3">
          <label for="id_album" class="form-label">Pilih Album</label>
          <select class="form-select form-control-lg" name="id_album" id="id_album" required>
            <option value="" disabled selected>Pilih Album</option>
            <?php
            $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE id_user='$id_user'");
            while ($data_album = mysqli_fetch_array($sql_album)) { ?>
              <option value="<?php echo $data_album['id_album'] ?>"><?php echo $data_album['nama_album'] ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="lokasi_file" class="form-label">Pilih File</label>
          <input type="file" class="form-control form-control-lg" name="lokasi_file" id="lokasi_file" required>
        </div>

        <button type="submit" class="btn btn-primary w-100" name="tambah">Tambah Foto</button>

      </form>
    </div>
  </div>
</div>

<div class="col-md-8">
    <div class="card mt-3 shadow-sm border-light">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Galeri Foto</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Judul Foto</th>
                        <th>Deskripsi</th>
                        <th>Pengguna</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Query untuk admin menampilkan foto dari semua pengguna
                    $sql = mysqli_query($koneksi, "SELECT * FROM foto INNER JOIN user ON foto.id_user=user.id_user");
                    while ($data = mysqli_fetch_array($sql)) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><img src="../assets/img/<?php echo $data['lokasi_file'] ?>" width="100px" class="img-thumbnail"></td>
                            <td><?php echo $data['judul_foto'] ?></td>
                            <td><?php echo $data['deskripsi_foto'] ?></td>
                            <td><?php echo $data['nama'] ?></td> <!-- Menampilkan nama pengguna yang mengunggah foto -->
                            <td><?php echo $data['tanggal_unggah'] ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['id_foto'] ?>">
                                    Edit
                                </button>
                                <div class="modal fade" id="edit<?php echo $data['id_foto'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Data Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../config/admin_aksi_foto.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_foto" value="<?php echo $data['id_foto'] ?>">

                    <div class="mb-4">
                        <label for="judul_foto" class="form-label">Judul Foto</label>
                        <input type="text" name="judul_foto" id="judul_foto" value="<?php echo $data['judul_foto'] ?>" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi_foto" class="form-label">Deskripsi</label>
                        <textarea class="form-control form-control-lg" name="deskripsi_foto" id="deskripsi_foto" rows="5" required><?php echo $data['deskripsi_foto']; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="id_album" class="form-label">Pilih Album</label>
                        <select class="form-select form-control-lg" name="id_album" id="id_album" required>
                            <?php
                            $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE id_user='$id_user'");
                            while ($data_album = mysqli_fetch_array($sql_album)) { ?>
                                <option value="<?php echo $data_album['id_album'] ?>" 
                                <?php if ($data_album['id_album'] == $data['id_album']) { echo 'selected="selected"'; } ?>>
                                    <?php echo $data_album['nama_album'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="lokasi_file" class="form-label">Ganti Foto</label>
                        <div class="row">
                            <div class="col-md-4">
                                <img src="../assets/img/<?php echo $data['lokasi_file'] ?>" width="100px" class="img-fluid rounded border">
                            </div>
                            <div class="col-md-8">
                                <input type="file" class="form-control" name="lokasi_file">
                            </div>
                        </div>
                    </div>
                    
            </div>
            <div class="modal-footer">
                <button type="submit" name="edit" class="btn btn-primary btn-lg w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>


                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['id_foto'] ?>">
                                    Hapus
                                </button>
                                <div class="modal fade" id="hapus<?php echo $data['id_foto'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Hapus Foto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../config/aksi_admin_foto.php" method="POST">
                                                    <input type="hidden" name="id_foto" value="<?php echo $data['id_foto'] ?>">
                                                    Apakah Anda yakin ingin menghapus foto <strong><?php echo $data['judul_foto'] ?></strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


        </div>
    </div>



    <footer class="d-flex justify-content-center border-top mt-3" style="background-color: #000000; color: #ffffff; position: fixed; bottom: 0; width: 100%; padding: 10px 0;">
    <p>&copy; UKK PPLG 2024 | Revan Fadhillah Sonjaya</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</body>

</html>