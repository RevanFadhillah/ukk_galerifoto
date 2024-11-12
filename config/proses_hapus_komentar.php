<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('Anda belum login.');
            location.href='../login.php'; // Redirect ke halaman login
          </script>";
    exit(); // Pastikan eksekusi berhenti setelah redirect
}

$id_komentar = isset($_GET['id_komentar']) ? $_GET['id_komentar'] : null;
$id_foto = isset($_GET['id_foto']) ? $_GET['id_foto'] : null;

// Pastikan id_komentar dan id_foto ada di URL
if ($id_komentar && $id_foto) {
    // Sanitasi input untuk mencegah SQL Injection
    $id_komentar = mysqli_real_escape_string($koneksi, $id_komentar);
    $id_foto = mysqli_real_escape_string($koneksi, $id_foto);

    // Ambil data komentar untuk memeriksa pemiliknya
    $query = "SELECT id_user FROM komentar_foto WHERE id_komentar = '$id_komentar' AND id_foto = '$id_foto'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $comment_data = mysqli_fetch_assoc($result);
        $comment_owner_id = $comment_data['id_user'];
        
        // Admin dapat menghapus komentar dari siapa pun, sementara user hanya bisa menghapus komentar mereka sendiri
        if ($_SESSION['role'] == 'admin' || $_SESSION['id_user'] == $comment_owner_id) {
            // Query untuk menghapus komentar berdasarkan ID
            $query_delete = "DELETE FROM komentar_foto WHERE id_komentar = '$id_komentar' AND id_foto = '$id_foto'";
            
            if (mysqli_query($koneksi, $query_delete)) {
                // Jika berhasil, redirect ke halaman yang sesuai
                if ($_SESSION['role'] == 'admin') {
                    // Jika admin, redirect ke halaman admin
                    echo "<script>
                            alert('Komentar berhasil dihapus.');
                            location.href='../admin/admin_index.php'; // Redirect ke halaman admin
                          </script>";
                } else {
                    // Jika user biasa, redirect ke halaman user
                    echo "<script>
                            alert('Komentar berhasil dihapus.');
                            location.href='../admin/index.php'; // Redirect ke halaman user
                          </script>";
                }
                exit(); // Hentikan eksekusi lebih lanjut setelah redirect
            } else {
                // Jika gagal menghapus, tampilkan pesan error
                echo "<script>
                        alert('Gagal menghapus komentar.');
                        location.href='../admin/index.php'; // Kembali ke halaman admin
                      </script>";
                exit();
            }
        } else {
            // Jika user mencoba menghapus komentar yang bukan miliknya
            echo "<script>
                    alert('Anda tidak memiliki hak untuk menghapus komentar ini.');
                    location.href='../admin/index.php'; // Kembali ke halaman admin
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Komentar tidak ditemukan.');
                location.href='../admin/index.php'; // Kembali ke halaman admin
              </script>";
        exit();
    }
} else {
    // Jika tidak ada ID komentar atau ID foto yang diberikan
    echo "<script>
            alert('ID komentar atau ID foto tidak ditemukan.');
            location.href='../admin/index.php'; // Kembali ke halaman admin
          </script>";
    exit();
}
?>
