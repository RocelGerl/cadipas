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
    <title>Registrar Puntaje de Subcampos - NASA</title>
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
            --neon-green: rgb(0, 255, 102);
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

        h2 {
            color: var(--neon-blue);
            margin-bottom: 30px;
            text-shadow: 0 0 10px var(--neon-blue);
            letter-spacing: 3px;
            font-weight: 700;
            font-size: clamp(1.5rem, 4vw, 2rem);
            text-transform: uppercase;
        }

        h5 {
            color: var(--neon-blue);
            margin-bottom: 30px;
            text-shadow: 0 0 10px var(--neon-blue);
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .form-label {
            color: var(--neon-blue);
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 0 0 5px var(--neon-blue);
        }

        .form-control,
        .form-select {
            background-color: rgba(5, 10, 48, 0.7);
            color: var(--neon-blue);
            border: 1px solid var(--neon-blue);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-family: 'Orbitron', sans-serif;
            transition: all 0.3s;
        }
        

        .form-control:focus,
        .form-select:focus {
            background-color: rgba(5, 10, 48, 0.9);
            color: var(--neon-blue);
            border-color: var(--neon-pink);
            box-shadow: 0 0 15px var(--neon-pink);
            outline: none;
        }

        .form-control[readonly] {
            background-color: rgba(5, 10, 48, 0.5);
            color: var(--star-white);
            border-color: var(--neon-green);
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

        .btn-primary {
            background: linear-gradient(135deg, var(--neon-blue), #0066FF);
            color: var(--space-blue);
        }

        .btn-secondary {
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

        /* Estilo para el slider de puntaje */
        .form-range {
            -webkit-appearance: none;
            width: 100%;
            height: 15px;
            background: rgba(5, 10, 48, 0.7);
            border-radius: 10px;
            border: 1px solid var(--neon-blue);
            outline: none;
            margin-bottom: 5px;
        }

        .form-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: var(--neon-blue);
            cursor: pointer;
            box-shadow: 0 0 10px var(--neon-blue);
            transition: all 0.3s;
        }

        .form-range::-webkit-slider-thumb:hover {
            background: var(--neon-pink);
            box-shadow: 0 0 15px var(--neon-pink);
        }

        .slider-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--neon-blue);
            text-align: center;
            text-shadow: 0 0 10px var(--neon-blue);
            margin-top: 10px;
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
        }

        @media (max-width: 576px) {
            :root {
                --side-margin: 0.75rem;
            }

            .panel-container {
                padding: 25px 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Efecto de partículas -->
    <div class="particles" id="particles-js"></div>
    <div class="panel-container">
        <h2><i class="fas fa-tents"></i> Registrar Puntaje de Subcampo</h2>
        <form action="../controller/subirPuntajeC.php" method="post">
            <div class="row pt-2 mb-2" style="border-top: 2px dotted  #00F5FF; border-bottom: 2px dotted  #00F5FF;">
                <h5>Datos de la Patrulla</h5>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="patrulla" class="form-label">Patrulla</label>
                        <input type="text" class="form-control" id="patrulla" name="patrulla" readonly>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="subcampo" class="form-label">Subcampo</label>
                        <input type="text" class="form-control" id="subcampo" name="subcampo" readonly>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6 ">
                    <div class="mb-3">
                        <label for="grupo" class="form-label">Grupo</label>
                        <input type="text" class="form-control" id="grupo" name="grupo" readonly>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="dia" class="form-label">Dia</label>
                        <select class="form-select text-center" id="dia" name="dia" required>
                            <option value="" selected disabled>Seleccione un dia</option>
                            <option value="2">Dia 2</option>
                            <option value="3">Dia 3</option>
                        </select>
                    </div>
                </div>
                <div class="col col-12">
                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora</label>
                        <select class="form-select text-center" id="hora" name="hora" required>
                            <option value="" selected disabled>Seleccione una hora</option>
                            <option value="Desayuno">Desayuno</option>
                            <option value="Almuerzo">Almuerzo</option>
                            <option value="Cena">Cena</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="higiene" class="form-label">Higiene</label>
                        <select class="form-select text-center" id="higiene" name="higiene" required>
                            <option value="" selected disabled>Selecciona un puntaje</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="presentacion" class="form-label">Presentacion</label>
                        <select class="form-select text-center" id="presentacion" name="presentacion" required>
                            <option value="" selected disabled>Selecciona un puntaje</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="trabajo_equipo" class="form-label">Trabajo en Equipo</label>
                        <select class="form-select text-center" id="trabajo_equipo" name="trabajo_equipo" required>
                            <option value="" selected disabled>Selecciona un puntaje</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="sabor" class="form-label">Sabor</label>
                        <select class="form-select text-center" id="sabor" name="sabor" required>
                            <option value="" selected disabled>Selecciona un puntaje</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                    <div class="mb-3">
                        <label for="tiempo" class="form-label">Tiempo de Preparacion</label>
                        <select class="form-select text-center" id="tiempo" name="tiempo" required>
                            <option value="" selected disabled>Selecciona un puntaje</option>
                            <option value="1">Primero</option>
                            <option value="2">Del Segundo en adelante</option>
                        </select>
                    </div>
                </div>
                <div class="col col-12 col-sm-6 col-lg-6">
                        <button type="submit" class="btn btn-panel btn-primary">
                            <i class="fas fa-save"></i> Registrar Puntaje
                        </button>
                    </div>
            </div>
        </form>
        <a href="calificarC.php" class="btn btn-panel btn-secondary">
            <i class="fas fa-qrcode"></i> Volver a Escanear
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Crear partículas
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles-js');
            const particleCount = window.innerWidth < 768 ? 20 : 30;

            function createParticle() {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                const marginWidth = parseFloat(getComputedStyle(document.body).paddingLeft);
                const availableWidth = window.innerWidth - (marginWidth * 2);

                const size = Math.random() * 3 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${marginWidth + (Math.random() * availableWidth)}px`;

                const duration = Math.random() * 10 + 10;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${Math.random() * 10}s`;
                particle.style.opacity = Math.random() * 0.5 + 0.1;

                particlesContainer.appendChild(particle);

                particle.addEventListener('animationiteration', function() {
                    this.style.left = `${marginWidth + (Math.random() * availableWidth)}px`;
                    this.style.opacity = Math.random() * 0.5 + 0.1;
                });
            }

            for (let i = 0; i < particleCount; i++) {
                createParticle();
            }

            // Obtener el valor del código QR desde la URL
            const urlParams = new URLSearchParams(window.location.search);
            const qrValue = urlParams.get('qr');

            // Mostrar la información en el formulario
            if (qrValue) {
                try {
                    const data = JSON.parse(decodeURIComponent(qrValue));
                    document.getElementById('patrulla').value = data.patrulla;
                    document.getElementById('subcampo').value = data.subcampo;
                    document.getElementById('grupo').value = data.grupo;
                } catch (error) {
                    console.error('Error al decodificar el código QR:', error);
                }
            }

            // Actualizar el valor del control deslizante
            const puntajeSlider = document.getElementById('puntaje');
            const puntajeValue = document.getElementById('puntaje-value');

            puntajeSlider.addEventListener('input', function() {
                puntajeValue.textContent = this.value;
            });

            // Configurar el botón para volver a escanear
            document.getElementById('volver-escanear-btn').onclick = function() {
                window.location.href = 'calificarS.php';
            };
        });
    </script>
</body>

</html>