<?php
session_start();
require_once("../model/conexion.php");
$conn = conectarDB();

// Recoger datos del POST
$patrulla = $_POST['patrulla'];
$subcampo = $_POST['subcampo'];
$grupo = $_POST['grupo'];
$actividad = $_POST['actividad'];
$puesto = $_POST['puesto'];
$trabajo_equipo = $_POST['trabajo_equipo'];
$reglas = $_POST['reglas'];
$creatividad = $_POST['creatividad'];
$espiritu = $_POST['espiritu'];
$dirigente = $_SESSION['usuario_id'];

// Obtener id_grupo
$query = 'SELECT id_grupo FROM grupos WHERE nombre = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $grupo);
$stmt->execute();
$stmt->bind_result($idG);
$stmt->fetch();
$stmt->close();

// Obtener id_subcampo
$queryS = 'SELECT id_subcampo FROM subcampos WHERE nombre = ?';
$stmtS = $conn->prepare($queryS);
$stmtS->bind_param('s', $subcampo);
$stmtS->execute();
$stmtS->bind_result($idS);
$stmtS->fetch();
$stmtS->close();

// Obtener id_patrulla
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
    header("Location: ../view/calificar.php");
    exit();
}

// Verificar si ya existe evaluación para esta patrulla y actividad
$check_query = "SELECT id_ea FROM evaluacion_actividades 
                WHERE id_actividad = ? AND id_patrulla = ?";
$stmt_check = $conn->prepare($check_query);
$stmt_check->bind_param(
    'ii',
    $actividad,
    $id_patrulla
);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['sweet_alert'] = [
        'icon' => 'warning',
        'title' => 'Evaluación existente',
        'text' => 'Esta patrulla ya tiene una evaluación registrada para esta actividad.',
    ];
    header("Location: ../view/calificar.php");
    exit();
}
$stmt_check->close();

// Obtener tipo de actividad
$consulta = "SELECT tipo FROM actividades WHERE id_actividad = $actividad";
$resultado = mysqli_query($conn, $consulta);
if ($resultado && mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $tipo = $fila['tipo'];
} else {
    $_SESSION['sweet_alert'] = [
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'No se encontró la actividad.',
    ];
    header("Location: ../view/calificar.php");
    exit();
}

if ($tipo == 1) {
    $actipo = 20;
    if ($puesto == 2) {
        $actipo = $actipo - 10;
    }
} else {
    $actipo = 10;
    if ($puesto == 2) {
        $actipo = $actipo - 5;
    }
}

$puntaje = intval($actipo + (($trabajo_equipo + $reglas + $creatividad + $espiritu) / 4));

$insert_query = "INSERT INTO evaluacion_actividades 
                (id_actividad, id_dirigente, id_patrulla, resultado, trabajo_equipo, reglas, creatividad, espiritu, puntaje)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($insert_query);
$stmt_insert->bind_param('iiiiiiiii', $actividad, $dirigente, $id_patrulla, $actipo, $trabajo_equipo, $reglas, $creatividad, $espiritu, $puntaje);

if ($stmt_insert->execute()) {
    $_SESSION['sweet_alert'] = [
        'icon' => 'success',
        'title' => 'Evaluación Guardada',
        'text' => 'Los datos se guardaron correctamente.',
    ];
    if ($actividad == 1 || $actividad == 31) {
        $sumar = "SELECT puntuacion1 FROM patrullas WHERE id_patrulla = $id_patrulla ";
        $resultado3 = mysqli_query($conn, $sumar);
        if ($resultado3 && mysqli_num_rows($resultado3) > 0) {
            $fila = mysqli_fetch_assoc($resultado3);
            $puntuacion1 = $fila['puntuacion1'];
            $puntuacion1 = $puntuacion1 + $puntaje;
            $actualizar = "UPDATE patrullas SET puntuacion1 = $puntuacion1 WHERE id_patrulla = $id_patrulla ";
            $resultado4 = mysqli_query($conn, $actualizar);
        }
    } else if ($actividad >= 4 && $actividad <= 12 || $actividad == 2) {
        $sumar = "SELECT puntuacion2 FROM patrullas WHERE id_patrulla = $id_patrulla ";
        $resultado3 = mysqli_query($conn, $sumar);
        if ($resultado3 && mysqli_num_rows($resultado3) > 0) {
            $fila = mysqli_fetch_assoc($resultado3);
            $puntuacion2 = $fila['puntuacion2'];
            $puntuacion2 = $puntuacion2 + $puntaje;
            $actualizar = "UPDATE patrullas SET puntuacion2 = $puntuacion2 WHERE id_patrulla = $id_patrulla ";
            $resultado4 = mysqli_query($conn, $actualizar);
        }
    } else if ($actividad >= 13 && $actividad <= 15 || $actividad == 3) {
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
        'text' => 'Hubo un problema al guardar la evaluación: ' . $conn->error,
    ];
}

$stmt_insert->close();
$conn->close();

header("Location: ../view/calificar.php");
exit();
