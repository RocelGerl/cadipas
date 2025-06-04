<?php
session_start();
require_once("../model/conexion.php");
$conn = conectarDB();

$patrulla = $_POST['patrulla'];
$subcampo = $_POST['subcampo'];
$grupo = $_POST['grupo'];
$dia = $_POST['dia'];
$hora = $_POST['hora'];
$higiene = $_POST['higiene'];
$presentacion = $_POST['presentacion'];
$trabajo_equipo = $_POST['trabajo_equipo'];
$sabor = $_POST['sabor'];
$tiempo = $_POST['tiempo'];
$dirigente = $_SESSION['usuario_id'];


$query = 'SELECT id_grupo FROM grupos WHERE nombre = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $grupo); 
$stmt->execute();
$stmt->bind_result($idG);
$stmt->fetch();
$stmt->close();

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
    header("Location: ../view/calificarC.php");
    exit();
}

$check_query = "SELECT id_cocina FROM cocina 
                WHERE hora = ? AND id_patrulla = ? AND dia = ?";
$stmt_check = $conn->prepare($check_query);
$stmt_check->bind_param('sii', $hora, $id_patrulla, $dia);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['sweet_alert'] = [
        'icon' => 'warning',
        'title' => 'Evaluación existente',
        'text' => 'Esta patrulla ya tiene una evaluación registrada para esta hora de cocina.',
    ];
    header("Location: ../view/calificarC.php");
    exit();
}
$stmt_check->close();

if($tiempo == 1){
    $tie = 5;
}else{
    $tie = 1;
}

$puntaje = intval($tie + (($higiene + $presentacion + $trabajo_equipo + $sabor) / 4));

$query = "INSERT INTO cocina ( id_dirigente, id_patrulla, dia, hora, higiene, presentacion, trabajo_equipo, sabor, tiempo, puntaje)
          VALUES ($dirigente, $id_patrulla, $dia, '$hora', $higiene, $presentacion, $trabajo_equipo, $sabor, $tie, $puntaje)";

$resultado2 = mysqli_query($conn, $query);

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

header("Location: ../view/calificarC.php");  
exit();
?>
