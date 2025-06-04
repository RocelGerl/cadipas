<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NASA - Acceso al Sistema</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- PNotify -->
    <link href="https://cdn.jsdelivr.net/npm/pnotify@4.0.0/dist/PNotify.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/pnotify@4.0.0/dist/PNotifyBrightTheme.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/pnotify@4.0.0/dist/iife/PNotify.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pnotify@4.0.0/dist/iife/PNotifyButtons.js"></script>
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

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Orbitron', sans-serif;
            background-color: var(--space-blue);
            color: var(--star-white);
            text-transform: uppercase;
        }

        .login-container {
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .row.h-100 {
            margin: 0;
        }

        .image-side {
            padding: 0;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Efecto de partículas */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .particle {
            position: absolute;
            background-color: var(--neon-blue);
            border-radius: 50%;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) scale(0.5);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            80% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) scale(1.2);
                opacity: 0;
            }
        }

        /* Formulario */
        .form-side {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(5, 10, 48, 0.8);
            position: relative;
            overflow: hidden;
        }

        .form-side::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to bottom right,
                    transparent 45%,
                    var(--neon-blue) 50%,
                    transparent 55%);
            opacity: 0.1;
            animation: shine 6s infinite linear;
        }

        @keyframes shine {
            0% {
                transform: rotate(0deg) translate(-50%, -50%);
            }

            100% {
                transform: rotate(360deg) translate(-50%, -50%);
            }
        }

        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: rgba(5, 10, 48, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 245, 255, 0.3);
            border: 1px solid var(--neon-blue);
            backdrop-filter: blur(5px);
            animation: neonGlow 2s infinite alternate;
            position: relative;
            z-index: 2;
        }

        @keyframes neonGlow {
            from {
                box-shadow: 0 0 10px rgba(0, 245, 255, 0.3), 0 0 20px rgba(0, 245, 255, 0.2);
            }

            to {
                box-shadow: 0 0 15px rgba(0, 245, 255, 0.4), 0 0 25px rgba(0, 245, 255, 0.3);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .logo i {
            font-size: 50px;
            color: var(--star-white);
            text-shadow: 0 0 10px var(--star-white);
        }

        .logo h2 {
            color: var(--star-white);
            font-weight: 700;
            letter-spacing: 3px;
            margin-top: 15px;
            text-shadow: 0 0 5px var(--star-white);
        }

        .form-label {
            color: var(--star-white);
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        /* Campo de usuario (sin cambios) */
        .form-control {
            background-color: rgba(0, 245, 255, 0.1);
            border: 1px solid var(--star-white);
            ;
            margin-bottom: 20px;
            transition: all 0.3s;
            height: 45px;
        }

        input[type="text"] {

            color: var(--star-white) !important;
        }

        .form-control:focus {
            background-color: rgba(0, 245, 255, 0.2);
            border-color: var(--neon-pink);
            box-shadow: 0 0 0 0.25rem rgba(255, 16, 240, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 1px;
        }

        /* Estilo especial para el campo de contraseña con planetas */
        .password-container {
            position: relative;
            margin-bottom: 20px;
        }

        .password-input {
            background-color: rgba(0, 245, 255, 0.1);
            border: 1px solid var(--star-white);
            color: transparent;
            /* Texto transparente para mostrar solo planetas */
            letter-spacing: 10px;
            /* Espacio para los planetas */
            padding-left: 12px;
            height: 45px;
            caret-color: transparent;
            /* Cursor visible */
        }

        .planets-container {
            position: absolute;
            top: 70%;
            left: 12px;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            height: 100%;
            pointer-events: none;
            gap: 6px;
            max-width: calc(100% - 24px);
            overflow: hidden;
        }

        .planet-char {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            position: relative;
            animation: planetRotate 6s infinite linear;
            box-shadow: 0 0 8px currentColor;
        }

        /* Estilos para planetas realistas */
        .planet-char::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow:
                inset 0 0 20px rgba(255, 255, 255, 0.2),
                0 0 30px currentColor;
            opacity: 0.7;
        }

        .planet-char::after {
            content: '';
            position: absolute;
            width: 30%;
            height: 30%;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            top: 15%;
            left: 20%;
        }

        @keyframes planetRotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Planetas con texturas y atmósferas */
        .planet-char {
            position: relative;
            border-radius: 50%;
            box-shadow:
                0 0 10px currentColor,
                inset 5px 0 15px rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }

        .planet-earth {
            background: radial-gradient(circle at 30% 30%, #00F5FF 0%, #0066FF 100%);
            box-shadow: 0 0 15px #00F5FF;
        }

        .planet-mars {
            background: radial-gradient(circle at 20% 20%, #FF4D4D 0%, #CC0000 100%);
            box-shadow: 0 0 15px rgba(255, 77, 77, 0.7);
        }

        .planet-jupiter {
            background:
                radial-gradient(circle at 70% 30%, #FFA647 0%, #FF5E00 100%),
                linear-gradient(45deg,
                    transparent 45%,
                    rgba(255, 200, 150, 0.3) 50%,
                    transparent 55%);
        }

        .planet-saturn {
            background: radial-gradient(circle at 30% 30%, #E1D45A 0%, #D4AF37 100%);
            position: relative;
        }

        .planet-saturn::after {
            /* Anillos de Saturno */
            content: '';
            position: absolute;
            width: 200%;
            height: 10%;
            background: linear-gradient(90deg,
                    transparent 20%,
                    rgba(225, 212, 90, 0.7) 50%,
                    transparent 80%);
            top: 50%;
            left: -50%;
            transform: rotate(-15deg);
        }

        /* Estilo para el indicador de planetas adicionales */
        .planet-indicator {
            color: var(--neon-blue);
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
            display: flex;
            align-items: center;
            animation: pulse 1s infinite alternate;
        }

        /* Animación para el indicador */
        @keyframes pulse {
            from {
                opacity: 0.6;
            }

            to {
                opacity: 1;
                text-shadow: 0 0 5px var(--neon-blue);
            }
        }

        /* Asegurar que el input tenga suficiente espacio para los planetas */
        .password-input {
            padding-right: 20px;
            /* Espacio para el indicador */
            letter-spacing: 6px;
            /* Reducir espacio entre planetas */
        }

        .btn-login {
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-pink));
            border: none;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            transition: all 0.3s;
            color: var(--space-blue);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
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

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 245, 255, 0.4);
        }

        @media (max-width: 992px) {
            .image-side {
                height: 300px;
            }

            .login-container {
                height: auto;
            }

            .login-form {
                margin: 30px 20px;
                padding: 30px;
            }

            .image-side {
                display: none;
            }

            .form-side {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Notificaciones responsivas */
        .ui-pnotify-container {
            max-width: 100% !important;
            width: 100% !important;
        }

        @media (max-width: 768px) {
            .ui-pnotify-container {
                border-radius: 0 !important;
                margin: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
            }

            .ui-pnotify-title {
                font-size: 1.1rem !important;
            }

            .ui-pnotify-text {
                font-size: 0.9rem !important;
            }

            .ui-pnotify-icon .fas {
                font-size: 1.2rem !important;
            }
        }

        /* Efecto de vibración mejorado */
        @keyframes vibrate {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
                transform: translateX(5px);
            }
        }

        .vibrate {
            animation: vibrate 0.4s linear;
        }
    </style>
</head>

<body>
    <div class="container-fluid login-container">
        <div class="row h-100 g-0">
            <!-- Lado izquierdo con imagen al 100% -->
            <div class="col-lg-6 col-md-12 image-side p-0">
                <img src="../assets/img/astronauta.jpg" alt="Inicio de sesión NASA" class="w-100 h-100">
                <div class="particles" id="particles-js"></div>
            </div>

            <!-- Lado derecho con formulario -->
            <div class="col-lg-6 col-md-12 form-side">
                <div class="login-form">
                    <div class="logo">
                        <i class="fas fa-user-astronaut"></i>
                        <h2 class="mt-3">INGRESAR</h2>
                    </div>

                    <form id="loginForm" method="post" action="../controller/verificaLogin.php">
                        <!-- Campo de usuario (sin cambios) -->
                        <div class="mb-3">
                            <label for="usuario" class="form-label">USUARIO</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="astronauta 01" required>
                        </div>

                        <!-- Campo de contraseña con planetas -->
                        <div class="mb-3 password-container">
                            <label for="password" class="form-label">CONTRASEÑA</label>
                            <input type="password" class="form-control password-input" id="password" name="password" required placeholder="*********">
                            <div class="planets-container" id="planets-container"></div>
                        </div>

                        <button type="submit" class="btn btn-login" id="ingresar">
                            <i class="fas fa-rocket"></i> INGRESAR
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Crear partículas animadas
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles-js');
            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                createParticle();
            }

            function createParticle() {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                const size = Math.random() * 3 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;

                const duration = Math.random() * 10 + 10;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${Math.random() * 10}s`;
                particle.style.opacity = Math.random() * 0.5 + 0.1;

                particlesContainer.appendChild(particle);

                particle.addEventListener('animationiteration', function() {
                    this.style.left = `${Math.random() * 100}%`;
                    this.style.opacity = Math.random() * 0.5 + 0.1;
                });
            }
        });
    </script>
    <script>
        // Sistema de planetas para contraseña
        const passwordInput = document.getElementById('password');
        const planetsContainer = document.getElementById('planets-container');

        // Tipos de planetas con colores diferentes
        const planetTypes = [{
                class: 'planet-earth',
                color: '#00F5FF',
                name: 'Tierra'
            },
            {
                class: 'planet-mars',
                color: '#FF4D4D',
                name: 'Marte'
            },
            {
                class: 'planet-jupiter',
                color: '#FFA647',
                name: 'Júpiter'
            },
            {
                class: 'planet-saturn',
                color: '#E1D45A',
                name: 'Saturno'
            },
            {
                class: 'planet-neptune',
                color: '#6BFFE8',
                name: 'Neptuno'
            },
            {
                class: 'planet-venus',
                color: '#FF85F1',
                name: 'Venus'
            },
            {
                class: 'planet-uranus',
                color: '#7BFF7B',
                name: 'Urano'
            },
            {
                class: 'planet-mercury',
                color: '#C4C4C4',
                name: 'Mercurio'
            }
        ];

        // Configuración de planetas
        const PLANET_CONFIG = {
            baseSize: 14,
            sizeVariation: 4,
            gap: 6,
            minAnimationDuration: 3,
            animationVariation: 4
        };

        function updatePlanets() {
            planetsContainer.innerHTML = '';
            const length = passwordInput.value.length;

            if (length === 0) return;

            // Calcular espacio disponible
            const inputStyle = window.getComputedStyle(passwordInput);
            const availableWidth = passwordInput.offsetWidth -
                parseFloat(inputStyle.paddingLeft) -
                parseFloat(inputStyle.paddingRight) - 30; // Espacio para indicador

            // Calcular máximo número de planetas visibles
            const planetWidth = PLANET_CONFIG.baseSize + PLANET_CONFIG.gap;
            const maxVisiblePlanets = Math.floor(availableWidth / planetWidth);
            const visiblePlanets = Math.min(length, maxVisiblePlanets);

            // Crear planetas visibles
            for (let i = 0; i < visiblePlanets; i++) {
                const planetType = planetTypes[i % planetTypes.length];
                const size = PLANET_CONFIG.baseSize + Math.random() * PLANET_CONFIG.sizeVariation;
                const animationDuration = PLANET_CONFIG.minAnimationDuration +
                    Math.random() * PLANET_CONFIG.animationVariation;

                const planet = document.createElement('div');
                planet.className = `planet-char ${planetType.class}`;
                planet.style.width = `${size}px`;
                planet.style.height = `${size}px`;
                planet.style.animationDuration = `${animationDuration}s`;
                planet.style.backgroundColor = planetType.color;

                // Tooltip opcional (requiere Bootstrap)
                planet.setAttribute('data-bs-toggle', 'tooltip');
                planet.setAttribute('title', planetType.name);

                planetsContainer.appendChild(planet);
            }

            // Mostrar indicador si hay caracteres ocultos
            if (length > maxVisiblePlanets) {
                const hiddenChars = length - maxVisiblePlanets;
                const indicator = document.createElement('div');
                indicator.className = 'planet-indicator';
                indicator.textContent = `+${hiddenChars}`;
                indicator.style.color = planetTypes[hiddenChars % planetTypes.length].color;
                planetsContainer.appendChild(indicator);
            }

            // Inicializar tooltips (si se usan)
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        }

        // Event listeners
        passwordInput.addEventListener('input', updatePlanets);
        passwordInput.addEventListener('focus', updatePlanets);
        window.addEventListener('resize', updatePlanets);

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            updatePlanets();

            // Ocultar cursor de texto
            passwordInput.addEventListener('click', function() {
                this.blur();
                this.focus();
            });

            passwordInput.addEventListener('mousedown', function(e) {
                e.preventDefault();
                this.focus();
            });
        });
    </script>
    <!-- <script>
        function validateCredentials(usuario, password) {
            const validCredentials = [{
                    user: 'astronauta01',
                    pass: 'nasa2023'
                },
                {
                    user: 'admin',
                    pass: 'admin123'
                },
                {
                    user: 'test',
                    pass: 'test'
                }
            ];
            return validCredentials.some(cred => cred.user === usuario.toLowerCase() && cred.pass === password);
        }

        // Función para mostrar errores
        function showError(message) {
            document.getElementById('loginForm').classList.add('vibrate');
            setTimeout(() => {
                document.getElementById('loginForm').classList.remove('vibrate');
            }, 500);
        }

        // Función para mostrar éxito
        function showSuccess() {
            window.location.href = 'panel.php';
        }

        // Manejador del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const usuario = document.getElementById('usuario').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = this.querySelector('button[type="submit"]');

            if (!usuario || !password) {
                showError('Por favor complete todos los campos');
                return;
            }

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> VERIFICANDO';
            submitBtn.disabled = true;

            setTimeout(() => {
                if (validateCredentials(usuario, password)) {
                    showSuccess();
                } else {
                    showError('Usuario o contraseña incorrectos');
                    document.getElementById('usuario').value = '';
                    document.getElementById('password').value = '';
                    document.getElementById('planets-container').innerHTML = '';
                }
                submitBtn.innerHTML = '<i class="fas fa-rocket"></i> INGRESAR';
                submitBtn.disabled = false;
            }, 800);
        });

        // Sistema de planetas (asegúrate de que solo exista una declaración)
        passwordInput = document.getElementById('password');
        planetsContainer = document.getElementById('planets-container');

        passwordInput.addEventListener('input', function() {
            planetsContainer.innerHTML = '';
            const length = passwordInput.value.length;

            if (length === 0) return;

            for (let i = 0; i < length; i++) {
                const planet = document.createElement('div');
                planet.className = 'planet-char';
                planetsContainer.appendChild(planet);
            }
        });
    </script> -->
</body>

</html>