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
    <title>Panel de Control - NASA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');

        :root {
            --space-blue: #050A30;
            --neon-blue: #00F5FF;
            --neon-pink: #FF10F0;
            --star-white: #E6E6E6;
            --neon-green: #00ff66;
            --neon-yellow: #FFFF00;
            --neon-orange: rgb(255, 149, 0);
            --neon-red: rgb(255, 0, 102);
            --side-margin: 2rem;
            /* Margen lateral base */
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
            /* Márgenes laterales */
            background-image:
                radial-gradient(circle at 20% 30%, rgba(0, 245, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(255, 16, 240, 0.1) 0%, transparent 20%);
        }

        /* Ajustes responsivos para márgenes */
        @media (max-width: 992px) {
            :root {
                --side-margin: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            :root {
                --side-margin: 1rem;
            }
        }

        @media (max-width: 576px) {
            :root {
                --side-margin: 0.75rem;
            }
        }

        .panel-container {
            width: 100%;
            max-width: 800px;
            padding: 40px 30px;
            margin: 2rem 0;
            /* Margen vertical */
            background: rgba(5, 10, 48, 0.7);
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 245, 255, 0.3);
            border: 2px solid var(--neon-blue);
            backdrop-filter: blur(10px);
            text-align: center;
            animation: neonGlow 3s infinite alternate;
        }

        /* Resto de tus estilos permanecen igual... */
        h1 {
            color: var(--neon-blue);
            margin-bottom: 50px;
            text-shadow: 0 0 10px var(--neon-blue);
            letter-spacing: 3px;
            font-weight: 700;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            /* Texto responsivo */
        }

        .btn-panel {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            font-size: 1.2rem;
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
            gap: 15px;
        }

        .btn-positions {
            background: linear-gradient(135deg, var(--neon-blue), #0066FF);
            color: var(--space-blue);
        }

        .btn-rate {
            background: linear-gradient(135deg, var(--neon-pink), #CC00FF);
            color: var(--space-blue);
        }

        .btn-exit {
            background: linear-gradient(135deg, var(--neon-red), #ff0000);
            color: var(--space-blue);
        }

        .btn-taller {
            background: linear-gradient(135deg, var(--neon-yellow), rgb(149, 149, 12));
            color: var(--space-blue);
        }

        .btn-cocina {
            background: linear-gradient(135deg, var(--neon-orange), rgb(136, 80, 3));
            color: var(--space-blue);
        }

        .btn-panel:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 245, 255, 0.4);
        }

        .btn-rate2 {
            background: linear-gradient(135deg, var(--neon-green), #097936);
            color: var(--space-blue);
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

        .particles {
            position: fixed;
        }
    </style>
</head>

<body>
    <!-- Efecto de partículas -->
    <div class="particles" id="particles-js"></div>

    <div class="panel-container">
        <h1><i class="fas fa-user-astronaut"></i> Panel de Control de <br><?php echo $_SESSION['nombre']; ?></h1>

        <a href="tabla-posiciones.php" class="btn btn-panel btn-positions">
            <i class="fas fa-table"></i> Ver Tabla de Posiciones
        </a>
        <!-- Botones de Calificacion -->
        <?php
        if ($_SESSION['rol'] == 1) { ?>
            <a href="calificar.php" class="btn btn-panel btn-rate">
                <i class="fas fa-star"></i> Calificar Actividades
            </a>
            <a href="calificarT.php" class="btn btn-panel btn-taller">
                <i class="fas fa-book"></i> Calificar Talleres
            </a>
            <a href="calificarC.php" class="btn btn-panel btn-cocina">
                <i class="fas fa-kitchen-set"></i> Calificar Cocina
            </a>
            <a href="calificarS.php" class="btn btn-panel btn-rate2">
                <i class="fas fa-tents"></i> Calificar Subcampos
            </a>
        <?php } else if ($_SESSION['rol'] == 2) { ?>
            <a href="calificar.php" class="btn btn-panel btn-rate">
                <i class="fas fa-star"></i> Calificar Actividades
            </a>
            <a href="calificarC.php" class="btn btn-panel btn-cocina">
                <i class="fas fa-kitchen-set"></i> Calificar Cocina
            </a>
            <a href="calificarS.php" class="btn btn-panel btn-rate2">
                <i class="fas fa-tents"></i> Calificar Subcampos
            </a>
        <?php } else if ($_SESSION['rol'] == 3) { ?>
            <a href="calificarT.php" class="btn btn-panel btn-taller">
                <i class="fas fa-book"></i> Calificar Talleres
            </a>
            <a href="calificarC.php" class="btn btn-panel btn-cocina">
                <i class="fas fa-kitchen-set"></i> Calificar Cocina
            </a>
            <a href="calificarS.php" class="btn btn-panel btn-rate2">
                <i class="fas fa-tents"></i> Calificar Subcampos
            </a>
        <?php } else if ($_SESSION['rol'] == 4) { ?>
            <a href="calificarC.php" class="btn btn-panel btn-cocina">
                <i class="fas fa-kitchen-set"></i> Calificar Cocina
            </a>
            <a href="calificarS.php" class="btn btn-panel btn-rate2">
                <i class="fas fa-tents"></i> Calificar Subcampos
            </a>
        <?php } else if ($_SESSION['rol'] == 5) { ?>
            <a href="calificar.php" class="btn btn-panel btn-rate">
                <i class="fas fa-star"></i> Calificar Actividades
            </a>
            <a href="calificarT.php" class="btn btn-panel btn-taller">
                <i class="fas fa-book"></i> Calificar Talleres
            </a>
        <?php } else if ($_SESSION['rol'] == 6) { ?>
            <a href="calificar.php" class="btn btn-panel btn-rate">
                <i class="fas fa-star"></i> Calificar Actividades
            </a>
        <?php } else if ($_SESSION['rol'] == 7) { ?>
            <a href="calificarT.php" class="btn btn-panel btn-taller">
                <i class="fas fa-book"></i> Calificar Talleres
            </a>
        <?php }  else if ($_SESSION['rol'] == 8) { ?>
        <?php } ?>
        <a href="../controller/cerrarSesion.php" class="btn btn-panel btn-exit">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Crear partículas animadas (ajustadas para márgenes)
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles-js');
            const particleCount = window.innerWidth < 768 ? 20 : 30;

            function createParticle() {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Ajustar posición considerando márgenes
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
        });
    </script>
</body>

</html>