<?php
session_start();
require_once("../model/conexion.php");
$conn = conectarDB();

$patrulla = $_POST['patrulla'];
$subcampo = $_POST['subcampo'];
$grupo = $_POST['grupo'];
$actividad = $_POST['taller'];
$asistencia = $_POST['asistencia'];
$practica = $_POST['practica'];
$trabajo_equipo = $_POST['trabajo_equipo'];
$creatividad = $_POST['creatividad'];
$participacion = $_POST['participacion'];
$dirigente = $_SESSION['usuario_id'];

// Preparar la consulta para obtener el id_grupo
$query = 'SELECT id_grupo FROM grupos WHERE nombre = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $grupo);  // 's' significa que es una cadena
$stmt->execute();
$stmt->bind_result($idG); // Asignar el valor a la variable $id
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
$resultado1 = mysqli_query($conn, $consulta1);
if ($resultado1 && mysqli_num_rows($resultado1) > 0) {
    $fila = mysqli_fetch_assoc($resultado1);
    $id_patrulla = $fila['id_patrulla'];
} else {
    $_SESSION['sweet_alert'] = [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'No se encontró la patrulla.',
    ];
    header("Location: ../view/calificarT.php");
    exit();
}

// Verificar si ya existe evaluación para esta patrulla y actividad
$check_query = "SELECT id_t FROM evaluacion_talleres 
                WHERE id_actividad = ? AND id_patrulla = ?";
$stmt_check = $conn->prepare($check_query);
$stmt_check->bind_param('ii', $actividad,
 $id_patrulla);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['sweet_alert'] = [
        'icon' => 'warning',
        'title' => 'Evaluación existente',
        'text' => 'Esta patrulla ya tiene una evaluación registrada para este taller.',
    ];
    header("Location: ../view/calificarT.php");
    exit();
}
$stmt_check->close();


if ($asistencia == 1) {
    $asis = 5;
} else if ($asistencia == 2) {
    $asis = 2; 
} else {
    $asis = 0;
}
if ($practica == 1) {
    $pract = 5;
} else if ($practica == 2) {
    $pract = 3; 
} else {
    $pract = 1;
}
if ($trabajo_equipo == 1) {
    $te = 5;
} else if ($trabajo_equipo == 2) {
    $te = 3; 
} else {
    $te = 1;
}
if ($creatividad == 1) {
    $crea = 5;
} else if ($creatividad == 2) {
    $crea = 3; 
} else {
    $crea = 1;
}
if ($participacion == 1) {
    $part = 5;
} else if ($participacion == 2) {
    $part = 3; 
} else {
    $part = 1;
}


$puntaje = intval($asis + (($pract + $te + $crea + $part) / 4));

$query = "INSERT INTO evaluacion_talleres ( id_dirigente, id_patrulla, id_actividad, asistencia, practica, trabajo_equipo, creatividad, participacion, puntaje)
          VALUES ($dirigente, $id_patrulla, $actividad, $asis, $pract, $te, $crea, $part, $puntaje)";

$resultado2 = mysqli_query($conn, $query);

if ($resultado2) {
    // Si la inserción es exitosa, redirigimos con mensaje de éxito
    $_SESSION['sweet_alert'] = [
        'icon' => 'success',
        'title' => 'Evaluación Guardada',
        'text' => 'Los datos se guardaron correctamente.',
    ];

    if($actividad>=16 && $actividad!=19 && $actividad!=20 && $actividad!=21){
        $sumar = "SELECT puntuacion2 FROM patrullas WHERE id_patrulla = $id_patrulla ";
        $resultado3 = mysqli_query($conn, $sumar);
        if ($resultado3 && mysqli_num_rows($resultado3) > 0) {
            $fila = mysqli_fetch_assoc($resultado3);
            $puntuacion2 = $fila['puntuacion2'];
            $puntuacion2 = $puntuacion2 + $puntaje;
            $actualizar = "UPDATE patrullas SET puntuacion2 = $puntuacion2 WHERE id_patrulla = $id_patrulla ";
            $resultado4 = mysqli_query($conn, $actualizar);
        }
    }else if($actividad>=19 && $actividad<=21){
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
    // Si ocurre un error al guardar, redirigimos con mensaje de error
    $_SESSION['sweet_alert'] = [
        'icon' => 'error',
        'title' => 'Error al guardar',
        'text' => 'Hubo un problema al guardar la evaluación.',
    ];
}

header("Location: ../view/calificarT.php");  // Redirigir a calificarS.php
exit();
?>
