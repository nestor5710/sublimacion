<?php
session_start();
require 'db_connect.php';
$message = '';
$messageType = ''; // 'success' or 'error'
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Registro
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);
        if (!empty($user) && !empty($pass)) {
            // Verificar si usuario existe
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$user]);
            if ($stmt->rowCount() > 0) {
                $message = "El usuario ya existe.";
                $messageType = 'error';
            } else {
                // Crear usuario
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                if ($stmt->execute([$user, $hashed_password])) {
                    $message = "Registro exitoso. ¡Ahora puedes iniciar sesión!";
                    $messageType = 'success';
                } else {
                    $message = "Error al registrar.";
                    $messageType = 'error';
                }
            }
        } else {
            $message = "Por favor completa todos los campos.";
            $messageType = 'error';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Login
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);
        if (!empty($user) && !empty($pass)) {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$user]);
            $row = $stmt->fetch();
            if ($row && password_verify($pass, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: generator.php");
                exit;
            } else {
                $message = "Usuario o contraseña incorrectos.";
                $messageType = 'error';
            }
        } else {
            $message = "Por favor completa todos los campos.";
            $messageType = 'error';
        }
    }
}
?>
