<?php
require_once("../model/conexion.php");

if (isset($_GET['id_patrulla'])) {
    $id_patrulla = $_GET['id_patrulla'];

    $conn = conectarDB();

    $sql = "SELECT id_subcampo FROM patrullas WHERE id_patrulla = $id_patrulla";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $id_subcampo = $row['id_subcampo'];

        $sql_subcampo = "SELECT nombre FROM subcampos WHERE id_subcampo = $id_subcampo";
        $result_subcampo = mysqli_query($conn, $sql_subcampo);

        if ($row_subcampo = mysqli_fetch_assoc($result_subcampo)) {
            echo json_encode(['subcampo' => $row_subcampo['nombre']]);
        } else {
            echo json_encode(['subcampo' => 'Subcampo no encontrado']); 
        }
    } else {
        echo json_encode(['subcampo' => '']);
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>
