<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Código QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        /* Estilos para el contenedor del código QR */
        #qr-code {
            position: relative;
            display: inline-block;
            margin-top: 20px;
        }

        /* Estilos para el pie de página */
        .qr-footer {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-5">Generar Código QR</h1>
        <div class="row justify-content-center mt-3">
            <div class="col-sm-6">
                <!-- Formulario para ingresar datos -->
                <form id="qr-form">
                    <div class="mb-3">
                        <label for="grupo" class="form-label">Grupo</label>
                        <select class="form-select" id="grupo" required>
                            <option value="" selected disabled>Seleccione un grupo</option>

                            <?php
                            require_once("../model/conexion.php");
                            $conn = conectarDB();
                            $sql = "SELECT * FROM grupos";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?php echo $row['nombre']; ?>"><?php echo $row['nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="patrulla" class="form-label">Nombre de la Patrulla</label>
                        <select class="form-select" id="patrulla" required>
                            <option value="" selected disabled>Seleccione una patrulla</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcampo" class="form-label">Subcampo</label>
                        <input type="text" class="form-control" id="subcampo" readonly required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Generar Código QR</button>
                </form>

                <!-- Contenedor para mostrar el código QR -->
                <div id="qr-code" class="text-center mt-4"></div>

                <!-- Botón para descargar el código QR -->
                <button id="download-btn" class="btn btn-success w-100 mt-3" style="display: none;">Descargar Código QR</button>

            </div>
        </div>
    </div>

    <script>
        // Manejar el cambio de grupo
        document.getElementById('grupo').addEventListener('change', function() {
            const grupo = this.value;
            const patrullaSelect = document.getElementById('patrulla');

            // Limpiar el select de patrullas
            patrullaSelect.innerHTML = '<option value="" selected disabled>Seleccione una patrulla</option>';

            // Realizar la solicitud AJAX para obtener las patrullas
            fetch(`../controller/patrullas.php?grupo=${grupo}`)
                .then(response => response.json())
                .then(patrullas => {
                    patrullas.forEach(patrulla => {
                        const option = document.createElement('option');
                        option.value = patrulla.id_patrulla;
                        option.textContent = patrulla.nombre;
                        // Guardar el nombre de la patrulla en un atributo 'data-nombre'
                        option.setAttribute('data-nombre', patrulla.nombre);
                        patrullaSelect.appendChild(option);
                    });
                });
        });

        // Manejar el cambio de patrulla
        document.getElementById('patrulla').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const idPatrulla = selectedOption.value;
            const nombrePatrulla = selectedOption.getAttribute('data-nombre'); // Obtener el nombre de la patrulla

            // Realizar la solicitud AJAX para obtener el subcampo de la patrulla seleccionada
            fetch(`../controller/subcampos.php?id_patrulla=${idPatrulla}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('subcampo').value = data.subcampo;
                });

            // Guardar el nombre de la patrulla para usarlo al generar el QR
            document.getElementById('patrulla').setAttribute('data-nombre', nombrePatrulla);
        });

        // Manejar el envío del formulario
        document.getElementById('qr-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe

            const patrulla = document.getElementById('patrulla').value;
            const grupo = document.getElementById('grupo').value;
            const subcampo = document.getElementById('subcampo').value;
            const nombrePat = document.getElementById('patrulla').getAttribute('data-nombre');
            if (!patrulla || !grupo || !subcampo) {
                alert('Por favor, complete todos los campos.');
                return;
            }

            const data = {
                patrulla: nombrePat,
                grupo: grupo,
                subcampo: subcampo
            };

            const jsonString = JSON.stringify(data);
            const qrCodeContainer = document.getElementById('qr-code');
            qrCodeContainer.innerHTML = ''; // Limpiar contenido previo

            // Generar el código QR
            QRCode.toCanvas(jsonString, {
                width: 200
            }, function(error, canvas) {
                if (error) {
                    console.error('Error al generar el código QR:', error);
                    alert('Hubo un error al generar el código QR. Inténtalo de nuevo.');
                } else {
                    // Añadir un logo al código QR
                    const logo = new Image();
                    logo.src = '../assets/img/astronauta.jpg';
                    logo.onload = function() {
                        const ctx = canvas.getContext('2d');
                        const logoSize = 40;
                        const logoX = (canvas.width - logoSize) / 2;
                        const logoY = (canvas.height - logoSize) / 2;

                        ctx.drawImage(logo, logoX, logoY, logoSize, logoSize);

                        // Añadir el canvas con el código QR generado
                        qrCodeContainer.appendChild(canvas);

                        // Crear un pie de página con el nombre del grupo y la patrulla
                        const footer = document.createElement('div');
                        footer.classList.add('qr-footer');
                        footer.textContent = `${grupo}_${document.getElementById('patrulla').getAttribute('data-nombre')}`;
                        qrCodeContainer.appendChild(footer);

                        // Mostrar el botón de descarga
                        const downloadButton = document.getElementById('download-btn');
                        downloadButton.style.display = 'block';

                        // Asignar el evento de clic al botón de descarga
                        downloadButton.onclick = function() {
                            const link = document.createElement('a');
                            link.href = canvas.toDataURL('image/png'); // Obtener la imagen en formato PNG
                            link.download = `QR_${grupo}_${document.getElementById('patrulla').getAttribute('data-nombre')}.png`; // Nombre del archivo con el nombre del grupo y patrulla
                            link.click(); // Activar la descarga
                        };
                    };

                    logo.onerror = function() {
                        console.error('Error al cargar el logo.');
                        alert('Hubo un error al cargar el logo. Verifica la ruta de la imagen.');
                    };
                }
            });
        });
    </script>
</body>

</html>