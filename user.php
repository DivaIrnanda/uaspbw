<?php
// Koneksi ke database
include "koneksi.php";

// Folder untuk upload foto
$uploadDir = "img/";

// Pagination
$limit = 5;
$pagination = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$start = ($pagination - 1) * $limit;

// Query untuk menampilkan data
$result = $conn->query("SELECT * FROM user LIMIT $start, $limit");
$total = $conn->query("SELECT COUNT(id) AS total FROM user")->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);


// Tambah data
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Enkripsi password dengan MD5
    $fileName = $_FILES['foto']['name'];
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    if (!empty($fileName) && move_uploaded_file($fileTmpName, $filePath)) {
        // Jika foto diunggah
        $conn->query("INSERT INTO user (username, password, foto) VALUES ('$username', '$password', '$filePath')");
    } else {
        // Jika foto tidak diunggah
        $conn->query("INSERT INTO user (username, password) VALUES ('$username', '$password')");
    }
    header("Location: admin.php?page=user");
}

// Hapus data
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $data = $conn->query("SELECT foto FROM user WHERE id = $id")->fetch_assoc();
    if (!empty($data['foto']) && file_exists($data['foto'])) {
        unlink($data['foto']);
    }
    $conn->query("DELETE FROM user WHERE id = $id");
    header("Location: admin.php?page=user");
}

// Edit data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? md5($_POST['password']) : null; // Enkripsi password dengan MD5 jika diisi
    $fileName = $_FILES['foto']['name'];
    $fileTmpName = $_FILES['foto']['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    if (!empty($fileName)) {
        // Hapus foto lama
        $data = $conn->query("SELECT foto FROM user WHERE id = $id")->fetch_assoc();
        if (!empty($data['foto']) && file_exists($data['foto'])) {
            unlink($data['foto']);
        }
        // Upload foto baru
        move_uploaded_file($fileTmpName, $filePath);
        $conn->query("UPDATE user SET username = '$username', foto = '$filePath' WHERE id = $id");
    } else {
        $conn->query("UPDATE user SET username = '$username' WHERE id = $id");
    }

    // Update password jika diisi
    if ($password) {
        $conn->query("UPDATE user SET password = '$password' WHERE id = $id");
    }
    header("Location: admin.php?page=user");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">Manajemen User</h2>
        <!-- Tambah Data -->
        <button class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Tambah User
        </button>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = $start + 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['username'] ?></td>
                        <td>
                            <?php if (!empty($row['foto'])): ?>
                                <img src="<?= $row['foto'] ?>" alt="Foto" style="width: 100px;">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Tombol Edit -->
                            <a href="#" title="Edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <!-- Tombol Hapus -->
                            <a href="#" title="Delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id'] ?>">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" value="<?= $row['username'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Foto</label>
                                            <input type="file" class="form-control" name="foto">
                                            <small>Biarkan kosong jika tidak ingin mengganti foto</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="edit" class="btn btn-success">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="modalHapus<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalHapusLabel">Hapus User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Yakin ingin menghapus user ini?</p>
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger" name="hapus">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" name="foto">
                            <small>Biarkan kosong jika tidak ingin menambahkan foto</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add" class="btn btn-primary">Tambah</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <nav>
    <ul class="pagination justify-content-end">
        <li class="page-item <?= ($pagination == 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="admin.php?page=user&pagination=1">First</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $pagination) ? 'active' : '' ?>">
                <a class="page-link" href="admin.php?page=user&pagination=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= ($pagination == $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link" href="admin.php?page=user&pagination=<?= $totalPages ?>">Last</a>
        </li>
    </ul>
</nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
