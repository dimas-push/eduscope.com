<?php
$host = 'localhost'; // Nama Host
$dbname = 'eduscope'; // Nama Database
$username = 'root'; // Username Database
$password = ''; // Password Database


try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Koneksi gagal: " . $e->getMessage());
}


?>