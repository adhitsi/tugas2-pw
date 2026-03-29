<?php
session_start();
if(isset($_SESSION['username'])){
    header("Location: ../pages/dashboard.php");
    exit;
}
include '../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Sistem Manajemen Barang</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    background: linear-gradient(135deg, #4e73df, #1cc88a);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    background: transparent;
    border-bottom: none;
}

.form-control {
    border-radius: 10px;
}

.btn-primary {
    border-radius: 10px;
    background: #4e73df;
    border: none;
}

.btn-primary:hover {
    background: #2e59d9;
}

.logo {
    font-size: 40px;
}

.subtitle {
    font-size: 14px;
    color: #6c757d;
}
</style>

</head>

<body>

<div class="card shadow-lg p-4" style="width: 350px;">
    
    <div class="text-center mb-3">
        <div class="logo">📦</div>
        <h4 class="fw-bold">Manajemen Barang</h4>
        <div class="subtitle">Silakan login untuk melanjutkan</div>
    </div>

    <?php
    if(isset($_SESSION['error'])){
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login gagal',
                text: '".$_SESSION['error']."'
            });
        </script>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="../auth/proses_login.php" method="POST" id="loginForm">

        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Login
        </button>

    </form>

</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e){
    const username = this.username.value.trim();
    const password = this.password.value.trim();

    if(username === "" || password === ""){
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Username dan Password tidak boleh kosong!'
        });
    } else {
        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
});
</script>

</body>
</html>