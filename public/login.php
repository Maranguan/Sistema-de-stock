<?php
// Incluir la conexión a la base de datos
include '../includes/db.php'; // Nota que se usa '../' porque el archivo login.php está dentro de la carpeta 'public'

// Comprobar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Sanitizar el correo para evitar inyecciones
    $correo = mysqli_real_escape_string($conn, $correo);

    // Encriptar la contraseña ingresada con MD5
    $contrasena_encriptada = md5($contrasena);

    // Preparar la consulta SQL utilizando una consulta preparada para prevenir inyecciones SQL
    $sql = "SELECT * FROM usuarios WHERE correo = ? AND contrasena = ?";

    // Preparar la declaración
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("ss", $correo, $contrasena_encriptada);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        // Si el usuario existe
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            // Iniciar sesión y almacenar la información del usuario
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Redirigir al panel de control o a la página de inicio
            header("Location: panel.php");
            exit();
        } else {
            // Si las credenciales son incorrectas
            $error = "Correo o contraseña incorrectos.";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $error = "Error en la consulta. Por favor, intente más tarde.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Incluir Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir jQuery (para el funcionamiento de algunos componentes de Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Incluir Popper.js (requerido para algunos componentes de Bootstrap como modales) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <!-- Incluir Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Iniciar sesión</h2>
    <?php
    // Mostrar el mensaje de error si existe
    if (isset($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
    ?>
    <form method="POST" action="login.php" class="mt-4">
        <div class="form-group">
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
    </form>

    <div class="mt-3 text-center">
        <p>No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</div>

</body>
</html>
