<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Posiciones - NASA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');

        :root {
            --space-blue: #050A30;
            --neon-blue: #00F5FF;
            --neon-pink: #FF10F0;
            --star-white: #E6E6E6;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            background-color: var(--space-blue);
            color: var(--star-white);
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .main-container {
            padding: 1rem;
            width: 100vw;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .table-container {
            background: rgba(5, 10, 48, 0.3);
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 0 30px rgba(0, 245, 255, 0.2);
            border: 1px solid var(--neon-blue);
            width: 100%;
            box-sizing: border-box;
        }

        h1 {
            color: var(--neon-blue);
            text-shadow: 0 0 10px var(--neon-blue);
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0 1rem;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
        }

        /* ESTILOS CRÍTICOS PARA DATATABLES TRANSPARENTE */
        .dataTables_wrapper,
        .dataTables_scroll,
        .dataTables_scrollHead,
        .dataTables_scrollHeadInner,
        .dataTables_scrollBody,
        table.dataTable,
        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tbody th,
        table.dataTable tbody td {
            background-color: transparent !important;
            color: var(--star-white) !important;
            border-color: rgba(0, 245, 255, 0.3) !important;
        }

        .dataTables_scrollHeadInner table {
            background-color: transparent !important;
            margin-bottom: 0 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: transparent !important;
            color: var(--star-white) !important;
            border: 1px solid var(--neon-blue) !important;
        }

        table.dataTable.display tbody tr.odd>*,
        table.dataTable.display tbody tr.even>* {
            box-shadow: none !important;
            background-color: rgba(5, 10, 48, 0.5) !important;
        }

        table.dataTable.display tbody tr:hover>* {
            background-color: rgba(0, 245, 255, 0.15) !important;
        }



        /* Estilos para la tabla */
        #positionsTable {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse !important;
        }

        #positionsTable thead th {
            background-color: rgba(0, 245, 255, 0.1) !important;
            color: var(--neon-blue) !important;
            border-bottom: 2px solid var(--neon-blue) !important;
            position: sticky;
            top: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Columnas de puntos */
        .points-cell {
            position: relative;
            padding-right: 2.5rem !important;
        }

        .btn-details {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: 1px solid var(--neon-pink);
            color: var(--neon-pink);
            width: 1.8rem;
            height: 1.8rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 0.8rem;
        }

        .btn-details:hover {
            background: var(--neon-pink);
            color: var(--space-blue);
            box-shadow: 0 0 10px var(--neon-pink);
        }

        /* Estilos para controles de DataTables */
        .dataTables_info {
            color: var(--star-white) !important;
        }

        .dataTables_filter input {
            background-color: transparent !important;
            color: var(--star-white) !important;
            border: 1px solid var(--neon-blue) !important;
            border-radius: 4px;
            padding: 5px 10px;
        }

        .dataTables_filter input:focus {
            outline: none;
            box-shadow: 0 0 8px var(--neon-blue);
        }

        .dataTables_filter label {
            color: var(--star-white) !important;
        }

        .dataTables_length select {
            background-color: transparent;
            color: var(--star-white);
            border: 1px solid var(--neon-blue);
            border-radius: 4px;
            padding: 5px;
        }

        .dataTables_length select option {
            background-color: var(--space-blue);
        }

        .dataTables_length label {
            color: var(--star-white) !important;
        }

        /* Modal Styles */
        .modal-content {
            background-color: var(--space-blue);
            border: 1px solid var(--neon-blue);
        }

        .modal-header {
            border-bottom: 1px solid var(--neon-blue);
        }

        .modal-title {
            color: var(--neon-blue);
        }

        .btn-close {
            filter: invert(1);
        }

        .btn-back {
            background-color: var(--neon-blue);
        }

        .btn-back:hover {
            background-color: var(--neon-pink);
        }


        input[type="search"] {
            width: 75% !important;
        }

        /* Estilo para hacer las tablas responsivas dentro del modal */
        .modal-body table {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            display: block;
        }

        /* Asegurar que las celdas no hagan que la tabla se expanda demasiado */
        .modal-body table th,
        .modal-body table td {
            white-space: nowrap;
            padding: 0.5rem;
        }

        /* Opcional: estilo para la barra de desplazamiento */
        .modal-body table::-webkit-scrollbar {
            height: 8px;
        }

        .modal-body table::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .btn-back {
                background-color: var(--neon-blue);
                width: 100%;
            }
        }

        @media (max-width: 375px) {
            input[type="search"] {
                width: 70% !important;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <a href="panel.php" class="btn btn-back border rounded mb-3">Volver</a>
        <h1><i class="fas fa-table"></i> TABLA DE POSICIONES</h1>

        <div class="table-container">

            <table id="positionsTable" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patrulla</th>
                        <th>Subcampo</th>
                        <th>Grupo</th>
                        <th>Día 1</th>
                        <th>Día 2</th>
                        <th>Día 3</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once("../model/conexion.php");
                    $conn = conectarDB();
                    $suma = "UPDATE patrullas SET total = puntuacion1 + puntuacion2 + puntuacion3";
                    $res = mysqli_query($conn, $suma);

                    $sql = "SELECT pa.*, s.nombre AS subcampo, g.nombre AS grupo FROM patrullas pa 
                            JOIN subcampos s ON pa.id_subcampo = s.id_subcampo
                            JOIN grupos g ON pa.id_grupo = g.id_grupo
                            ORDER BY total DESC";
                    $contador = 0;
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $contador += 1;
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['subcampo']; ?></td>
                            <td><?php echo $row['grupo']; ?></td>
                            <td class="points-cell">
                                <?php echo $row['puntuacion1']; ?>
                                <button class="btn-details" data-bs-toggle="modal" data-bs-target="#detailsModal"
                                    data-day="1" data-patrol="<?php echo $row['nombre']; ?>" data-id="<?php echo $row['id_patrulla']; ?>" data-puntuacion1="<?php echo htmlspecialchars($row['puntuacion1'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            <td class="points-cell">
                                <?php echo $row['puntuacion2']; ?>
                                <button class="btn-details" data-bs-toggle="modal" data-bs-target="#detailsModal2"
                                    data-day="2" data-patrol="<?php echo $row['nombre']; ?>" data-id="<?php echo $row['id_patrulla']; ?>" data-puntuacion2="<?php echo htmlspecialchars($row['puntuacion2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            <td class="points-cell">
                                <?php echo $row['puntuacion3']; ?>
                                <button class="btn-details" data-bs-toggle="modal" data-bs-target="#detailsModal3"
                                    data-day="3" data-patrol="<?php echo $row['nombre']; ?>" data-id="<?php echo $row['id_patrulla']; ?>" data-puntuacion3="<?php echo htmlspecialchars($row['puntuacion3'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            <td><?php echo $row['total']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>



    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title w-100 text-center">
                        <span>Puntuacion</span> -
                        Día <span>1</span>
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- Juego Masivo -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Juego Masivo</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Juego</th>
                                        <th>Resultado</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Cumplimiento de Reglas</th>
                                        <th>Creatividad</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="modalDetailsBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title w-100 text-center">
                        <span>Puntuacion</span> -
                        Día <span>2</span>
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- Juego Masivo -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Juego Masivo</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Juego</th>
                                        <th>Resultado</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Cumplimiento de Reglas</th>
                                        <th>Creatividad</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="modalDetails2Body">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Juegos Competitivos -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Juegos Competitivos</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Juego</th>
                                        <th>Resultado</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Cumplimiento de Reglas</th>
                                        <th>Creatividad</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="juegosCompetitivosBody">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0">Talleres</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Taller</th>
                                        <th>Asistencia</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Habilidad Tecnica</th>
                                        <th>Creatividad</th>
                                        <th>Participacion</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="talleresBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0">Cocina</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Hora</th>
                                        <th>Higiene</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Presentacion</th>
                                        <th>Sabor</th>
                                        <th>Tiempo de Preparacion</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="CocinaBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Subcampo</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Formacion</th>
                                        <th>Uniformidad</th>
                                        <th>Limpieza</th>
                                        <th>Sistema de Equipos</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="Subcampo2Body">
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal3" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title w-100 text-center">
                        <span>Puntuacion</span> -
                        Día <span>3</span>
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- Juego Masivo -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Juego Masivo</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Juego</th>
                                        <th>Resultado</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Cumplimiento de Reglas</th>
                                        <th>Creatividad</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="modalDetails3Body">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Juegos Competitivos -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Juegos Competitivos</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Juego</th>
                                        <th>Resultado</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Cumplimiento de Reglas</th>
                                        <th>Creatividad</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="juegosCompetitivos3Body">
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="card mb-3">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0">Talleres</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Taller</th>
                                        <th>Asistencia</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Habilidad Tecnica</th>
                                        <th>Creatividad</th>
                                        <th>Participacion</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="talleres3Body">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0">Cocina</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Hora</th>
                                        <th>Higiene</th>
                                        <th>Trabajo en equipo</th>
                                        <th>Presentacion</th>
                                        <th>Sabor</th>
                                        <th>Tiempo de Preparacion</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="Cocina3Body">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Subcampo</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Formacion</th>
                                        <th>Uniformidad</th>
                                        <th>Limpieza</th>
                                        <th>Sistema de Equipos</th>
                                        <th>Espiritu Scout</th>
                                        <th>Puntuacion</th>
                                    </tr>
                                </thead>
                                <tbody id="Subcampo3Body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#positionsTable').DataTable({
                responsive: true,
                scrollX: true,
                scrollCollapse: true,
                fixedHeader: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                initComplete: function() {
                    // Limpieza inicial
                    $('.dataTables_wrapper').removeClass('no-footer');
                    $('.dataTables_scrollHead').css('background', 'transparent');
                    $('.dataTables_scrollHeadInner').css({
                        'background': 'transparent',
                        'padding': '0'
                    });
                    $('.dataTables_scrollBody').css('box-shadow', 'none');

                    // Asegurar que el header tenga el estilo correcto
                    $('.dataTables_scrollHead thead th').css({
                        'background-color': 'rgba(0, 245, 255, 0.1)',
                        'color': '#00F5FF'
                    });
                },
                createdRow: function(row, data, dataIndex) {
                    // Estilizar filas nuevas
                    $(row).css({
                        'background-color': 'rgba(5, 10, 48, 0.5)',
                        'color': '#E6E6E6'
                    });
                },
                drawCallback: function() {
                    // Limpieza después de dibujar
                    $('.odd, .even').removeClass('odd even').css('background', 'transparent');

                    // Reforzar estilos
                    $('.dataTables_wrapper').css('background-color', 'transparent');
                    $('#positionsTable tbody tr').css('background-color', 'rgba(5, 10, 48, 0.5)');
                }
            });
        });
    </script>

    <!-- Dia 1 -->
    <script>
        document.getElementById('detailsModal')?.addEventListener('show.bs.modal', async (event) => {
            const button = event.relatedTarget;
            const idPatrulla = button.getAttribute('data-id');
            const tableModel = document.getElementById('modalDetailsBody');
            const puntuacion1 = button.getAttribute('data-puntuacion1');
            Promise.all([
                    fetch('../controller/dia1.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json())
                ])
                .then(([data1]) => {
                    let tableModel1 = document.getElementById('modalDetailsBody');
                    tableModel1.innerHTML = '';
                    if (data1 && data1.length > 0) {
                        data1.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.nombre_actividad}</td>
                    <td class='text-center'>${row.resultado}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.reglas}</td>
                    <td class='text-center'>${row.creatividad}</td>
                    <td class='text-center'>${row.espiritu}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel1.appendChild(newRow);
                        });
                    } else {
                        tableModel1.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }
                })
        });
    </script>
    <!-- Dia 2 -->
    <script>
        document.getElementById('detailsModal2').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const idPatrulla = button.getAttribute('data-id');
            const nombrePatrulla = button.getAttribute('data-patrol');

            Promise.all([
                    fetch('../controller/dia2.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia2C.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia2T.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia2Co.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia2Sub.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json())
                ])
                .then(([data1, data2, data3, data4, data5]) => {

                    // Juegos Masivos
                    let tableModel1 = document.getElementById('modalDetails2Body');
                    tableModel1.innerHTML = '';
                    if (data1 && data1.length > 0) {
                        data1.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.nombre_actividad}</td>
                    <td class='text-center'>${row.resultado}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.reglas}</td>
                    <td class='text-center'>${row.creatividad}</td>
                    <td class='text-center'>${row.espiritu}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel1.appendChild(newRow);
                        });
                    } else {
                        tableModel1.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    // Juegos Competitivos
                    let tableModel2 = document.getElementById('juegosCompetitivosBody');
                    tableModel2.innerHTML = '';
                    if (data2 && data2.length > 0) {
                        data2.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td  class='text-center'>${row.nombre_actividad}</td>
                    <td  class='text-center'>${row.resultado}</td>
                    <td  class='text-center'>${row.trabajo_equipo}</td>
                    <td  class='text-center'>${row.reglas}</td>
                    <td  class='text-center'>${row.creatividad}</td>
                    <td  class='text-center'>${row.espiritu}</td>
                    <td  class='text-center'>${row.puntaje}</td>`;
                            tableModel2.appendChild(newRow);
                        });
                    } else {
                        tableModel2.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    // Talleres
                    let tableModel3 = document.getElementById('talleresBody');
                    tableModel3.innerHTML = '';
                    if (data3 && data3.length > 0) {
                        data3.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.nombre_actividad}</td>
                    <td class='text-center'>${row.asistencia}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.practica}</td>
                    <td class='text-center'>${row.creatividad}</td>
                    <td class='text-center'>${row.participacion}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel3.appendChild(newRow);
                        });
                    } else {
                        tableModel3.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    // Cocina
                    let tableModel4 = document.getElementById('CocinaBody');
                    tableModel4.innerHTML = '';
                    if (data4 && data4.length > 0) {
                        data4.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.hora}</td>
                    <td class='text-center'>${row.higiene}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.presentacion}</td>
                    <td class='text-center'>${row.sabor}</td>
                    <td class='text-center'>${row.tiempo}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel4.appendChild(newRow);
                        });
                    } else {
                        tableModel4.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    //subcampo
                    let tableModel5 = document.getElementById('Subcampo2Body');
                    tableModel5.innerHTML = '';
                    if (data5 && data5.length > 0) {
                        data5.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.formacion}</td>
                    <td class='text-center'>${row.uniformidad}</td>
                    <td class='text-center'>${row.limpieza}</td>
                    <td class='text-center'>${row.sistema_equipos}</td>
                    <td class='text-center'>${row.espiritu}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel5.appendChild(newRow);
                        });
                    } else {
                        tableModel5.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }
                })
        });
    </script>

    <!-- Dia 3 -->
    <script>
        document.getElementById('detailsModal3').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const idPatrulla = button.getAttribute('data-id');
            const nombrePatrulla = button.getAttribute('data-patrol');

            Promise.all([
                    fetch('../controller/dia3.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia3C.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),


                    fetch('../controller/dia3Co.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia3Sub.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),

                    fetch('../controller/dia3T.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_patrulla=' + encodeURIComponent(idPatrulla)
                    }).then(res => res.json()),
                ])
                .then(([data1, data2, data3, data4, data5]) => {

                    // Juegos Masivos
                    let tableModel1 = document.getElementById('modalDetails3Body');
                    tableModel1.innerHTML = '';
                    if (data1 && data1.length > 0) {
                        data1.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.nombre_actividad}</td>
                    <td class='text-center'>${row.resultado}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.reglas}</td>
                    <td class='text-center'>${row.creatividad}</td>
                    <td class='text-center'>${row.espiritu}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel1.appendChild(newRow);
                        });
                    } else {
                        tableModel1.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    // Juegos Competitivos
                    let tableModel2 = document.getElementById('juegosCompetitivos3Body');
                    tableModel2.innerHTML = '';
                    if (data2 && data2.length > 0) {
                        data2.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.nombre_actividad}</td>
                    <td class='text-center'>${row.resultado}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.reglas}</td>
                    <td class='text-center'>${row.creatividad}</td>
                    <td class='text-center'>${row.espiritu}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel2.appendChild(newRow);
                        });
                    } else {
                        tableModel2.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    // Cocina
                    let tableModel4 = document.getElementById('Cocina3Body');
                    tableModel4.innerHTML = '';
                    if (data3 && data3.length > 0) {
                        data3.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.hora}</td>
                    <td class='text-center'>${row.higiene}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.presentacion}</td>
                    <td class='text-center'>${row.sabor}</td>
                    <td class='text-center'>${row.tiempo}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel4.appendChild(newRow);
                        });
                    } else {
                        tableModel4.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                    //subcampo
                    let tableModel5 = document.getElementById('Subcampo3Body');
                    tableModel5.innerHTML = '';
                    if (data4 && data4.length > 0) {
                        data4.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.formacion}</td>
                    <td class='text-center'>${row.uniformidad}</td>
                    <td class='text-center'>${row.limpieza}</td>
                    <td class='text-center'>${row.sistema_equipos}</td>
                    <td class='text-center'>${row.espiritu}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel5.appendChild(newRow);
                        });
                    } else {
                        tableModel5.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }

                     // Talleres
                    let tableModel3 = document.getElementById('talleres3Body');
                    tableModel3.innerHTML = '';
                    if (data5 && data5.length > 0) {
                        data5.forEach(row => {
                            let newRow = document.createElement('tr');
                            newRow.innerHTML = `
                    <td class='text-center'>${row.nombre_actividad}</td>
                    <td class='text-center'>${row.asistencia}</td>
                    <td class='text-center'>${row.trabajo_equipo}</td>
                    <td class='text-center'>${row.practica}</td>
                    <td class='text-center'>${row.creatividad}</td>
                    <td class='text-center'>${row.participacion}</td>
                    <td class='text-center'>${row.puntaje}</td>`;
                            tableModel3.appendChild(newRow);
                        });
                    } else {
                        tableModel3.innerHTML = "<tr><td colspan='7' class='text-center text-danger'>No se encontraron resultados</td></tr>";
                    }
                })
        });
    </script>

</body>

</html>