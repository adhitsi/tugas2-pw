<?php
session_start();
include '../config/koneksi.php';

$action = $_REQUEST['action'];

if ($action == 'tambah') {
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori_id'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $kode = $_POST['kode_barang'];

    $cek = $conn->prepare("SELECT id FROM barang WHERE kode_barang=?");
    $cek->bind_param("s", $kode);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['error'] = "Kode barang sudah ada!";
        header("Location: ../pages/dashboard.php");
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO barang (kode_barang, nama_barang, kategori_id, stok, harga) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssiid", $kode, $nama, $kategori, $stok, $harga);
    $stmt->execute();
    $stmt->close();

    header("Location: ../pages/dashboard.php");
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori_id'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $cek = $conn->prepare("SELECT id FROM barang WHERE kode_barang=?");
    $cek->bind_param("s", $kode);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['error'] = "Kode barang sudah ada!";
        header("Location: ../pages/dashboard.php");
        exit;
    }
    $stmt = $conn->prepare("UPDATE barang SET kode_barang=?, nama_barang=?, kategori_id=?, stok=?, harga=? WHERE id=?");
    $stmt->bind_param("ssiidi", $kode, $nama, $kategori, $stok, $harga, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../pages/dashboard.php");
}

if ($action == 'hapus') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM barang WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../pages/dashboard.php");
}
