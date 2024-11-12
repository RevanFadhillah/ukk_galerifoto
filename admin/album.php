<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
  echo "<script>
          alert('Anda belum login atau bukan user');
          location.href='../login.php'; // Redirect ke halaman login
        </script>";
  exit();
}
$id_user = $_SESSION['id_user'];
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

  <div class="container">
    <div class="row">
      <div class="col-md-4">
      <div class="card mt-4 shadow-lg border-light">
  <div class="card-header text-center bg-primary text-white">
    <h4 class="m-0">Tambah Album</h4>
  </div>
  <div class="card-body">
    <form action="../config/aksi_album.php" method="POST">
      <div class="mb-3">
        <label for="nama_album" class="form-label">Nama Album</label>
        <input type="text" name="nama_album" id="nama_album" class="form-control" placeholder="Masukkan nama album" required>
      </div>
      <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="4" placeholder="Deskripsi album" required></textarea>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary" name="tambah">Tambah Data</button>
      </div>
    </form>
  </div>
</div>
</div>

<div class="col-md-8">
  <div class="card mt-4 shadow-lg border-light">
    <div class="card-header bg-primary text-white">
      <h4 class="m-0">Data Album</h4>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama Album</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $id_user = $_SESSION['id_user'];
          $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE id_user='$id_user'");
          while ($data = mysqli_fetch_array($sql)) {
          ?>
            <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama_album'] ?></td>
              <td><?php echo $data['deskripsi'] ?></td>
              <td><?php echo $data['tanggal_buat'] ?></td>
              <td>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['id_album'] ?>">
                  Edit
                </button>

                <div class="modal fade" id="edit<?php echo $data['id_album'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Data Album</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../config/aksi_album.php" method="POST">
          <input type="hidden" name="id_album" value="<?php echo $data['id_album'] ?>">

          <div class="mb-3">
            <label for="nama_album" class="form-label">Nama Album</label>
            <input type="text" name="nama_album" id="nama_album" value="<?php echo $data['nama_album'] ?>" class="form-control form-control-lg" placeholder="Masukkan Nama Album" required>
          </div>

          <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control form-control-lg" rows="4" placeholder="Masukkan Deskripsi Album" required><?php echo $data['deskripsi']; ?></textarea>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['id_album'] ?>">
                  Hapus
                </button>

                <div class="modal fade" id="hapus<?php echo $data['id_album'] ?>" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="hapusModalLabel">Hapus Data Album</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="../config/aksi_album.php" method="POST">
                          <input type="hidden" name="id_album" value="<?php echo $data['id_album'] ?>">
                          Apakah Anda Yakin Ingin Menghapus Album <strong><?php echo $data['nama_album'] ?></strong>?
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="hapus" class="btn btn-danger">Hapus Data</button>
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