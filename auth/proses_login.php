<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) === 1){
        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            // Kirim flag untuk SweetAlert di dashboard
            $_SESSION['login_success'] = true;

            // Redirect ke dashboard (INI YANG PENTING)
            header("Location: ../pages/dashboard.php");
            exit;

        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit;
        }

    } else {
        $_SESSION['error'] = "Username tidak ditemukan!";
        header("Location: login.php");
        exit;
    }

} else {
    header("Location: login.php");
    exit;
}
?>