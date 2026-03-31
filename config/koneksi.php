<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "manajemen_barang";

$conn = new mysqli("localhost", "user_barang", "password_kamu", "db_barang");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>