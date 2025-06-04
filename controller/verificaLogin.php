<?php
session_start(); // Iniciar sesión (debe ser lo primero)
require_once("../model/conexion.php");

$conn = conectarDB();

// Datos del formulario
$usuario = $_POST["usuario"];
$password = $_POST["password"];

// Sanitizar entradas (evitar inyección SQL)
$usuario = mysqli_real_escape_string($conn, $usuario);
$password = mysqli_real_escape_string($conn, $password);

// Consulta SQL tradicional
$query = "SELECT * FROM dirigentes WHERE usuario = '$usuario' AND contraseña = '$password'";

$resultado = mysqli_query($conn, $query);
if ($resultado->num_rows > 0) {
    // Guardar datos en sesión
    $fila = $resultado->fetch_assoc();
    $_SESSION['autenticado'] = true;
    $_SESSION['usuario_id'] = $fila['id_dirigente'];
    $_SESSION['nombre_usuario'] = $fila['usuario'];
    $_SESSION['nombre'] = $fila['nombre'];
    $_SESSION['rol'] = $fila['rol'];

    header("Location: ../view/panel.php");
    
} else {
    $_SESSION['error_login'] = "Usuario o contraseña incorrectos";
    header("Location: ../view/login.php");
}

$conn->close();
exit();
?>