<?php
session_start();
require_once("../model/conexion.php");
$conn = conectarDB();

$patrulla = $_POST['patrulla'];
$subcampo = $_POST['subcampo'];
$grupo = $_POST['grupo'];
$dia = $_POST['dia'];
$formacion = $_POST['formacion'];
$uniformidad = $_POST['uniformidad'];
$limpieza = $_POST['limpieza'];
$sistemaequipos = $_POST['sistemaequipos'];
$espiritu = $_POST['espiritu'];
$dirigente = $_SESSION['usuario_id'];

$query = 'SELECT id_grupo FROM grupos WHERE nombre = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $grupo); 
$stmt->execute();
$stmt->bind_result($idG);
$stmt->fetch();
$stmt->close();

// Preparar la consulta para obtener el id_subcampos
$queryS = 'SELECT id_subcampo FROM subcampos WHERE nombre = ?';
$stmtS = $conn->prepare($queryS);
$stmtS->bind_param('s', $subcampo);
$stmtS->execute();
$stmtS->bind_result($idS);
$stmtS->fetch();
$stmtS->close();


$consulta1 = "SELECT id_patrulla FROM patrullas WHERE nombre = '$patrulla' AND id_subcampo = $idS AND id_grupo = $idG";
$resultado = mysqli_query($conn, $consulta1);
if ($resultado && mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $id_patrulla = $fila['id_patrulla'];
} else {
    // Si no se encuentra la patrulla, redirigimos con mensaje de error
    $_SESSION['sweet_alert'] = [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'No se encontró la patrulla.',
    ];
    header("Location: ../view/calificarS.php");
    exit();
}

$puntaje = intval((($formacion + $uniformidad + $limpieza + $sistemaequipos + $espiritu)*20) / 50);
// Usar consultas preparadas para seguridad y evitar errores de sintaxis
$query = "INSERT INTO evaluacion_sub (id_subcampo, id_patrulla, id_dirigente, dia, formacion, uniformidad, limpieza, sistema_equipos, espiritu, puntaje) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param(
    $stmt,
    "iiisiiiiii",
    $subcampo,
    $id_patrulla,
    $dirigente,
    $dia,
    $formacion,
    $uniformidad,
    $limpieza,
    $sistemaequipos,
    $espiritu,
    $puntaje
);

$resultado2 = mysqli_stmt_execute($stmt);

if ($resultado2) {
    $_SESSION['sweet_alert'] = [
        'icon' => 'success',
        'title' => 'Evaluación Guardada',
        'text' => 'Los datos se guardaron correctamente.',
    ];

    if($dia == 2){
        $sumar = "SELECT puntuacion2 FROM patrullas WHERE id_patrulla = $id_patrulla ";
        $resultado3 = mysqli_query($conn, $sumar);
        if ($resultado3 && mysqli_num_rows($resultado3) > 0) {
            $fila = mysqli_fetch_assoc($resultado3);
            $puntuacion2 = $fila['puntuacion2'];
            $puntuacion2 = $puntuacion2 + $puntaje;
            $actualizar = "UPDATE patrullas SET puntuacion2 = $puntuacion2 WHERE id_patrulla = $id_patrulla ";
            $resultado4 = mysqli_query($conn, $actualizar);
        }
    }else if($dia == 3){
        $sumar = "SELECT puntuacion3 FROM patrullas WHERE id_patrulla = $id_patrulla ";
        $resultado3 = mysqli_query($conn, $sumar);
        if ($resultado3 && mysqli_num_rows($resultado3) > 0) {
            $fila = mysqli_fetch_assoc($resultado3);
            $puntuacion3 = $fila['puntuacion3'];
            $puntuacion3 = $puntuacion3 + $puntaje;
            $actualizar = "UPDATE patrullas SET puntuacion3 = $puntuacion3 WHERE id_patrulla = $id_patrulla ";
            $resultado4 = mysqli_query($conn, $actualizar);
        }
    }


} else {
    $_SESSION['sweet_alert'] = [
        'icon' => 'error',
        'title' => 'Error al guardar',
        'text' => 'Hubo un problema al guardar la evaluación.',
    ];
}

header("Location: ../view/calificarS.php");  
exit();
