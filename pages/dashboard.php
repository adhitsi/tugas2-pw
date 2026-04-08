<?php
session_start();

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Data Barang</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: 12px;
        }

        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">Dashboard</span>
            <div>
                <span class="me-3">Halo, <b><?php echo $_SESSION['username']; ?></b></span>
                <button id="btnLogout" class="btn btn-danger btn-sm">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <!-- CARD -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h6>Total Barang</h6>
                        <h3>
                            <?php
                            $total = $conn->query("SELECT COUNT(*) as jml FROM barang")->fetch_assoc();
                            echo $total['jml'];
                            ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERT LOGIN -->
        <?php if (isset($_SESSION['login_success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Selamat datang 👋',
                    html: 'Login sebagai <b><?php echo $_SESSION['username']; ?></b>',
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        <?php unset($_SESSION['login_success']);
        endif; ?>

        <!-- ALERT SUCCESS -->
        <?php if (isset($_SESSION['success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '<?php echo $_SESSION['success']; ?>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            </script>
        <?php unset($_SESSION['success']);
        endif; ?>

        <!-- ALERT ERROR -->
        <?php if (isset($_SESSION['error'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '<?php echo $_SESSION['error']; ?>',
                });
            </script>
        <?php unset($_SESSION['error']);
        endif; ?>

        <!-- BUTTON -->
        <button class="btn btn-success mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Barang
        </button>

        <!-- TABLE -->
        <table id="barangTable" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT b.*, k.nama_kategori 
                FROM barang b 
                JOIN kategori k ON b.kategori_id = k.id";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['kode_barang']}</td>
                <td>{$row['nama_barang']}</td>
                <td>{$row['nama_kategori']}</td>
                <td>{$row['stok']}</td>
                <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                <td>
                    <button class='btn btn-primary btn-sm editBtn'
                        data-id='{$row['id']}'
                        data-kode='{$row['kode_barang']}'
                        data-nama='{$row['nama_barang']}'
                        data-kategori='{$row['kategori_id']}'
                        data-stok='{$row['stok']}'
                        data-harga='{$row['harga']}'>Edit</button>

                    <button class='btn btn-danger btn-sm deleteBtn' data-id='{$row['id']}'>Hapus</button>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../pages/proses_barang.php">
                    <div class="modal-header">
                        <h5>Tambah Barang</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="action" value="tambah">

                        <input type="text" name="kode_barang" class="form-control mb-2" placeholder="Kode Barang" required>
                        <input type="text" name="nama_barang" class="form-control mb-2" placeholder="Nama Barang" required>

                        <select name="kategori_id" class="form-control mb-2" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $kat = $conn->query("SELECT * FROM kategori");
                            while ($k = $kat->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kategori']}</option>";
                            }
                            ?>
                        </select>

                        <input type="number" name="stok" class="form-control mb-2" placeholder="Stok" required>
                        <input type="number" name="harga" class="form-control mb-2" placeholder="Harga" required>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../pages/proses_barang.php">
                    <div class="modal-header">
                        <h5>Edit Barang</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="editId">

                        <input type="text" name="kode_barang" id="editKode" class="form-control mb-2" required>
                        <input type="text" name="nama_barang" id="editNama" class="form-control mb-2" required>

                        <select name="kategori_id" id="editKategori" class="form-control mb-2" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $kat = $conn->query("SELECT * FROM kategori");
                            while ($k = $kat->fetch_assoc()) {
                                echo "<option value='{$k['id']}'>{$k['nama_kategori']}</option>";
                            }
                            ?>
                        </select>

                        <input type="number" name="stok" id="editStok" class="form-control mb-2" required>
                        <input type="number" name="harga" id="editHarga" class="form-control mb-2" required>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#barangTable').DataTable();

            // EDIT (FIXED)
            $(document).on('click', '.editBtn', function() {
                $('#editId').val($(this).data('id'));
                $('#editKode').val($(this).data('kode'));
                $('#editNama').val($(this).data('nama'));
                $('#editKategori').val($(this).data('kategori'));
                $('#editStok').val($(this).data('stok'));
                $('#editHarga').val($(this).data('harga'));

                new bootstrap.Modal(document.getElementById('modalEdit')).show();
            });

            // DELETE (FIXED)
            $(document).on('click', '.deleteBtn', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Data tidak bisa dipulihkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = '../pages/proses_barang.php?action=hapus&id=' + id;
                    }
                });
            });

            // LOGOUT
            $('#btnLogout').click(function() {
                Swal.fire({
                    title: 'Logout?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = '../auth/logout.php';
                    }
                });
            });

        });
    </script>

</body>

</html>