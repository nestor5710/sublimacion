<?php
$host = 'localhost'; // Ajusta esto si tu host es diferente en Hostinger (a veces es una IP o 'localhost')
$dbname = 'u409109107_sublimacion';
$username = 'u409109107_nestor5710sub'; // Cambiar por tu usuario real de Hostinger
$password = 'Siena_Fracc@119.5710'; // Cambiar por tu contraseña real de Hostinger
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
