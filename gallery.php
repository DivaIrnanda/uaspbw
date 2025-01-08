<?php
// Koneksi ke database
include "koneksi.php";

// Folder untuk upload gambar
$uploadDir = "img/";

// Pagination
$limit = 5;
$pagination = isset($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$start = ($pagination - 1) * $limit;

// Query untuk menampilkan data
$result = $conn->query("SELECT * FROM gallery ORDER BY tanggal DESC LIMIT $start, $limit");
$total = $conn->query("SELECT COUNT(id) AS total FROM gallery")->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Tambah data
if (isset($_POST['add'])) {
    $tanggal = date('Y-m-d H:i:s'); // Tanggal dan waktu otomatis
    $username = $_SESSION['username'];
    $fileName = $_FILES['gambar']['name'];
    $fileTmpName = $_FILES['gambar']['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    // Upload file ke folder server
    if (move_uploaded_file($fileTmpName, $filePath)) {
        $conn->query("INSERT INTO gallery (gambar, tanggal, username) VALUES ('$filePath', '$tanggal', '$username')");
    } else {
        echo "Gagal mengunggah gambar.";
    }
    header("Location: admin.php?page=gallery");
}

// Hapus data
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        // Hapus file gambar
        unlink($gambar);
    }
    $conn->query("DELETE FROM gallery WHERE id = $id");
    header("Location: admin.php?page=gallery");
}

// Edit data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $gambarLama = $_POST['gambar_lama'];
    $fileName = $_FILES['gambar']['name'];
    $fileTmpName = $_FILES['gambar']['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    if (!empty($fileName)) {
        // Upload file baru
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Update dengan gambar baru
            $conn->query("UPDATE gallery SET gambar = '$filePath' WHERE id = $id");
        } else {
            echo "Gagal mengunggah gambar baru.";
        }
    } else {
        // Tidak ada file baru, hanya simpan perubahan lain jika diperlukan
        // Jika ada kolom lain yang perlu diupdate, tambahkan logika di sini
    }
    header("Location: admin.php?page=gallery");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Gallery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <!-- Tambah Data -->
    <button class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg"></i> Tambah Gallery
    </button>
    <table class="table table-bordered">
        <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Detail</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = $start + 1;
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <img src="<?= $row['gambar'] ?>" alt="Gambar" style="width: 150px;">
                </td>
                <td>
                    pada: <?= $row['tanggal'] ?><br>
                    oleh: <?= $row['username'] ?><br>
                </td>
                <td width="50">
                    <!-- Tombol Edit -->
                    <a href="#" title="Edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row["id"] ?>">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <!-- Tombol Hapus -->
                    <a href="#" title="Delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row["id"] ?>">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </td>
            </tr>
            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $row["id"] ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $row["id"] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditLabel<?= $row["id"] ?>">Edit Gallery</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar Baru</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar">
                                </div>
                                <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                <input type="hidden" name="gambar_lama" value="<?= $row["gambar"] ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" name="edit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Hapus -->
            <div class="modal fade" id="modalHapus<?= $row["id"] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalHapusLabel">Hapus Gallery</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus gambar ini?</p>
                                <img src="<?= $row["gambar"] ?>" alt="Gambar" class="img-thumbnail" width="150">
                                <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                <input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
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
    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item <?= ($pagination == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="admin.php?page=gallery&pagination=1">First</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $pagination) ? 'active' : '' ?>">
                    <a class="page-link" href="admin.php?page=gallery&pagination=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($pagination == $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="admin.php?page=gallery&pagination=<?= $totalPages ?>">Last</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="add">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
