<?php
require_once("../model/conexion.php");

if (isset($_GET['grupo'])) {
    $grupo = $_GET['grupo'];

    // Establecer la conexión a la base de datos
    $conn = conectarDB();

    // Consulta para obtener las patrullas por grupo
    $sql = "SELECT * FROM patrullas WHERE id_grupo = (SELECT id_grupo FROM grupos WHERE nombre = '$grupo')";
    $result = mysqli_query($conn, $sql);

    // Crear el array de patrullas
    $patrullas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $patrullas[] = $row;
    }

    // Devolver las patrullas en formato JSON
    echo json_encode($patrullas);
}
?>
