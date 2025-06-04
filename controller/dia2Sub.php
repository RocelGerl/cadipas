<?php
header("Content-Type: application/json");
require_once("../model/conexion.php");
$conn = conectarDB();

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión Fallida: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['id_patrulla'])) {
        $idPatrulla = $conn->real_escape_string($_POST['id_patrulla']);

        $query = "SELECT * FROM evaluacion_sub WHERE id_patrulla = ? AND dia = 2";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idPatrulla);
        $stmt->execute();

        $result = $stmt->get_result();
        $registros = [];

        while ($row = $result->fetch_assoc()) {
            $registros[] = $row;
        }

        if (count($registros) > 0) {
            echo json_encode($registros);
        } else {
            echo json_encode(["error" => "No se encontraron registros para esta patrulla"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Parámetros incompletos"]);
    }
} else {
    echo json_encode(["error" => "Método no permitido"]);
}

$conn->close();
