<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['sweet_alert'])) {
    $alert = $_SESSION['sweet_alert'];  
    unset($_SESSION['sweet_alert']);    
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lector QR y Código de Barras - NASA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- PNotify -->
    <link href="https://cdn.jsdelivr.net/npm/pnotify@4.0.0/dist/pnotify.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/pnotify@4.0.0/dist/pnotify.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');

        :root {
            --space-blue: #050A30;
            --neon-blue: #00F5FF;
            --neon-pink: #FF10F0;
            --star-white: #E6E6E6;
            --neon-red: rgb(255, 0, 102);
            --side-margin: 2rem;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            background-color: var(--space-blue);
            color: var(--star-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            overflow-x: hidden;
            margin: 0;
            padding: 0 var(--side-margin);
            background-image:
                radial-gradient(circle at 20% 30%, rgba(0, 245, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(255, 16, 240, 0.1) 0%, transparent 20%);
        }

        .panel-container {
            width: 100%;
            max-width: 600px;
            padding: 40px 30px;
            margin: 2rem 0;
            background: rgba(5, 10, 48, 0.7);
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 245, 255, 0.3);
            border: 2px solid var(--neon-blue);
            backdrop-filter: blur(10px);
            text-align: center;
            animation: neonGlow 3s infinite alternate;
        }

        @keyframes neonGlow {
            0% {
                box-shadow: 0 0 40px rgba(0, 245, 255, 0.3);
            }

            50% {
                box-shadow: 0 0 60px rgba(0, 245, 255, 0.5);
            }

            100% {
                box-shadow: 0 0 40px rgba(0, 245, 255, 0.3);
            }
        }

        h1 {
            color: var(--neon-blue);
            margin-bottom: 30px;
            text-shadow: 0 0 10px var(--neon-blue);
            letter-spacing: 3px;
            font-weight: 700;
            font-size: clamp(1.5rem, 4vw, 2rem);
        }

        /* Estilos para el contenedor de la cámara */
        #reader {
            width: 100%;
            height: 300px;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--neon-blue);
            border-radius: 8px;
            display: none;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
        }

        /* Estilos para la imagen de referencia */
        #imagenReferencial {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--neon-blue);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
        }

        /* Contenedor para la imagen y la cámara */
        .scanner-wrapper {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        .btn-panel {
            width: 100%;
            margin: 15px auto;
            padding: 15px;
            font-size: 1rem;
            font-weight: bold;
            letter-spacing: 2px;
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
        }

        .btn-scan {
            background: linear-gradient(135deg, var(--neon-blue), #0066FF);
            color: var(--space-blue);
        }

        .btn-back {
            background: linear-gradient(135deg, var(--neon-red), #ff0000);
            color: var(--space-blue);
        }

        .btn-panel:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 245, 255, 0.4);
        }

        .btn-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to bottom right,
                    transparent 45%,
                    rgba(255, 255, 255, 0.3) 50%,
                    transparent 55%);
            transform: rotate(45deg);
            animation: btnShine 3s infinite linear;
        }

        @keyframes btnShine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        select {
            background-color: rgba(5, 10, 48, 0.7);
            color: var(--neon-blue);
            border: 1px solid var(--neon-blue);
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
        }

        select:focus {
            outline: none;
            box-shadow: 0 0 10px var(--neon-blue);
        }

        option {
            background-color: var(--space-blue);
            color: var(--neon-blue);
        }

        /* Efectos de partículas */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background-color: var(--neon-blue);
            border-radius: 50%;
            pointer-events: none;
            animation: float linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }

            50% {
                opacity: 0.8;
            }

            100% {
                transform: translateY(-100vh) translateX(20px);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .panel-container {
                padding: 30px 20px;
            }

            #reader,
            #imagenReferencial {
                height: 250px;
            }
        }

        @media (max-width: 576px) {
            :root {
                --side-margin: 0.75rem;
            }

            .panel-container {
                padding: 25px 15px;
            }

            #reader,
            #imagenReferencial {
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <?php if (isset($alert)): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $alert['icon']; ?>',
                title: '<?php echo $alert['title']; ?>',
                text: '<?php echo $alert['text']; ?>',
                background: 'rgba(5, 10, 48, 0.9)',
                color: '#E6E6E6',
                confirmButtonColor: '#00F5FF'
            });
        </script>
    <?php endif; ?>
    <div class="panel-container">
        <h1><i class="fas fa-qrcode"></i> Lector QR <br>Subcampos</h1>

        <!-- Contenedor para la imagen y la cámara -->
        <div class="scanner-wrapper">
            <!-- Imagen de referencia -->
            <img src="../assets/img/qr2.jpeg" class="img-fluid" alt="" id="imagenReferencial">
            <!-- Contenedor de la cámara -->
            <div id="reader" class="w-100"></div>
        </div>

        <select class="form-select" id="listaCamaras" onchange="camaraSeleccionada(this)">
            <option value="" selected>Seleccione una cámara</option>
        </select>

        <a href="panel.php" class="btn btn-panel btn-back">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener la lista de cámaras disponibles
            Html5Qrcode.getCameras().then(camaras => {
                const select = document.getElementById("listaCamaras");
                if (camaras && camaras.length) {
                    camaras.forEach(camara => {
                        const option = document.createElement("option");
                        option.value = camara.id;
                        option.textContent = camara.label || `Cámara ${select.length + 1}`;
                        select.appendChild(option);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se detectaron cámaras en el dispositivo.',
                        background: 'rgba(5, 10, 48, 0.9)',
                        color: '#E6E6E6',
                        confirmButtonColor: '#00F5FF'
                    });
                }
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso denegado',
                    text: 'No se pudo acceder a las cámaras. Verifique los permisos.',
                    background: 'rgba(5, 10, 48, 0.9)',
                    color: '#E6E6E6',
                    confirmButtonColor: '#00F5FF'
                });
                console.error('Error al obtener cámaras:', err);
            });

            // Verificar si hay un parámetro de éxito o error en la URL
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                PNotify.success({
                    title: 'Éxito',
                    text: 'Datos registrados correctamente',
                    delay: 3000,
                    styling: 'custom',
                    addclass: 'pnotify-custom'
                });
            } else if (status === 'error') {
                PNotify.error({
                    title: 'Error',
                    text: 'Hubo un problema al registrar los datos',
                    delay: 3000,
                    styling: 'custom',
                    addclass: 'pnotify-custom'
                });
            }
        });

        // Variable global para la instancia de Html5Qrcode
        let html5QrCode;

        function camaraSeleccionada(select) {
            const cameraId = select.value;
            if (cameraId) {
                // Ocultar la imagen de referencia
                document.getElementById("imagenReferencial").style.display = "none";
                // Mostrar el contenedor de la cámara
                document.getElementById("reader").style.display = "block";

                html5QrCode = new Html5Qrcode("reader");
                html5QrCode.start(
                    cameraId, {
                        fps: 10
                    },
                    qrCodeMessage => {
                        // Redirigir a la página de resultados con el valor escaneado
                        window.location.href = `qrLeidoS.php?qr=${encodeURIComponent(qrCodeMessage)}`;
                    },
                    errorMessage => {
                        console.error(`Error al escanear: ${errorMessage}`);
                    }
                ).catch(err => {
                    console.error('Error al iniciar la cámara:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo iniciar la cámara seleccionada',
                        background: 'rgba(5, 10, 48, 0.9)',
                        color: '#E6E6E6',
                        confirmButtonColor: '#00F5FF'
                    });
                });
            }
        }
    </script>
</body>

</html>