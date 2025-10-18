<?php
// Conectar a la base de datos
$db = new mysqli('localhost', 'root', '', 'esdras_library');

// Verificar conexión
if ($db->connect_error) {
    die('Error de conexión: ' . $db->connect_error);
}

// Datos del usuario
$username = 'biblioteca';
$email = 'biblioteca@esdras.com';
$password = password_hash('biblioteca123', PASSWORD_DEFAULT);

// Insertar o actualizar usuario
$sql = "INSERT INTO users (username, email, password) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE password = VALUES(password)";

$stmt = $db->prepare($sql);
$stmt->bind_param('sss', $username, $email, $password);

if ($stmt->execute()) {
    echo "Usuario creado/actualizado correctamente\n";
} else {
    echo "Error: " . $stmt->error . "\n";
}

$stmt->close();
$db->close();