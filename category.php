<?php
session_start();
include 'category-proses.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// Get all categories
$categories = getAllCategories($conn);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php if ($successMessage): ?>
        <div id="success-alert" class="alert alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Kembali</a>

    <div class="header">
        <h1>Kategori</h1>
    </div>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategory">
        Tambah Kategory
    </button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($categories) > 0) {
                $no = 1;
                foreach ($categories as $item) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['description'] ?></td>
                        <td>
                            <a href='javascript:void(0)' class='edit-link' data-bs-toggle='modal' data-bs-target='#addCategory' data-id='<?= $item['id'] ?>' data-name='<?= $item['name'] ?>' data-description='<?= $item['description'] ?>'>Edit</a>
                            <a href='category-proses.php?id=<?= $item['id'] ?>' onclick="return confirm('Apakah yakin akan menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>Belum ada data</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal Tambah/Update Kategory -->
    <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="category-proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="category-id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea rows="3" name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button name="submit" type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <script>
        // Populate the form for editing category
        document.querySelectorAll('.edit-link').forEach(link => {
            link.addEventListener('click', function() {
                document.getElementById('category-id').value = this.getAttribute('data-id');
                console.log(this.getAttribute('data-id'));

                document.getElementById('name').value = this.getAttribute('data-name');
                document.getElementById('description').value = this.getAttribute('data-description');
                document.querySelector('.modal-title').textContent = 'Edit Kategori';
            });
        });
    </script>

    <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>