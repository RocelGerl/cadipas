<?php
header("Content-Type: application/json");
require_once("../model/conexion.php");
$conn = conectarDB();

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexion Fallida: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['id_patrulla'])) {
    $idPatrulla = $conn->real_escape_string($_POST['id_patrulla']); // Usamos el id_patrulla


    $query = "
        SELECT * FROM cocina WHERE id_patrulla = $idPatrulla AND dia = 3

    ";
    $result = $conn->query($query);

    if (!$result) {
        die(json_encode(["error" => "Error en la consulta: " . $conn->error]));
    }

    // Verificamos si hay resultados
    $juegos = [];
    while ($row = $result->fetch_assoc()) {
        $juegos[] = $row;
    }

    // Si hay resultados, los retornamos
    if (count($juegos) > 0) {
        echo json_encode($juegos);
    } else {
        echo json_encode(["error" => "No se encontraron juegos para la patrulla con el ID proporcionado"]);
    }
} else {
    echo json_encode(["error" => "Método o parámetros incorrectos"]);
}

$conn->close();
?>
