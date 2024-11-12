<?php
session_start();
include '../config/koneksi.php';

// Memastikan admin sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Anda belum login atau bukan admin');
            location.href='../login.php'; // Redirect ke halaman login
          </script>";
    exit();
}

// Query untuk mengambil daftar pengguna
$query_users = mysqli_query($koneksi, "SELECT * FROM user");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pengguna - Admin</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <div class="container">
      <a class="navbar-brand text-white" href="admin_index.php"><i class="fa fa-camera-retro"></i> Website Galeri Foto</a>
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
            <a class="nav-link text-white" href="admin_pengguna.php"><i class="fa fa-users"></i> Pengguna</a>
          </li>
        </ul>
        <span class="navbar-text text-white ms-3">
          Hallo <?php echo $_SESSION['username']; ?>
        </span>
        <a href="../config/aksi_logout.php" class="btn btn-outline-danger ml-2 ms-4">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h2>Daftar Pengguna</h2>
    <table class="table">
      <thead>
        <tr>
          <th>id</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Menampilkan daftar pengguna
        while ($user = mysqli_fetch_array($query_users)) {
        ?>
          <tr>
            <td><?php echo $user['id_user']; ?></td>
            <td><?php echo $user['nama']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
              <!-- Tombol Edit -->
              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?php echo $user['id_user']; ?>">
                Edit
              </button>

              <!-- Tombol Hapus -->
              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $user['id_user']; ?>">
                Hapus
              </button>
            </td>
          </tr>

          <!-- Modal Edit User -->
          <div class="modal fade" id="edit<?php echo $user['id_user']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="editUserModalLabel">Edit Data User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="../config/aksi_user.php" method="POST">
                <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">

                <div class="mb-4">
                    <label for="username_user" class="form-label">Username</label>
                    <input type="text" name="username" id="username_user" value="<?php echo $user['username']; ?>" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label for="nama_user" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama_user" value="<?php echo $user['nama']; ?>" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label for="email_user" class="form-label">Email</label>
                    <input type="email" name="email" id="email_user" value="<?php echo $user['email']; ?>" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label for="password_user" class="form-label">Password</label>
                    <input type="password" name="password" id="password_user" class="form-control form-control-lg" placeholder="Masukkan password baru jika ingin diubah">
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" placeholder="Masukkan password untuk konfirmasi">
                </div>

                <button type="submit" name="edit_user" class="btn btn-primary btn-lg w-100">Simpan Perubahan</button>
                </form>

                </div>
              </div>
            </div>
          </div>

          <!-- Modal Hapus User -->
          <div class="modal fade" id="hapus<?php echo $user['id_user']; ?>" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteUserModalLabel">Hapus User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="../config/aksi_user.php" method="POST">
                    <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
                    <p>Apakah Anda yakin ingin menghapus user <strong><?php echo $user['nama']; ?></strong>?</p>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="hapus_user" class="btn btn-danger">Hapus</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <footer class="d-flex justify-content-center border-top mt-3" style="background-color: #000000; color: #ffffff; position: fixed; bottom: 0; width: 100%; padding: 10px 0;">
    <p>&copy; UKK PPLG 2024 | Revan Fadhillah Sonjaya</p>
  </footer>

  <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</body>

</html>
