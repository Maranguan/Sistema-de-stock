<?php
// Incluir la conexión y funciones
include '../includes/db.php';
include '../includes/funciones.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!usuario_logueado()) {
    header("Location: login.php");
    exit();
}

// Obtener los productos disponibles
$sql_productos = "SELECT * FROM productos";
$result_productos = $conn->query($sql_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <!-- Incluir Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php include '../includes/header.php'; ?>

<!-- Contenedor principal de la página -->
<div class="container mt-5">
    <h1 class="text-center mb-4">Productos Disponibles</h1>

    <!-- Mostrar productos disponibles -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nombre</th>
                <th>Cantidad Disponible</th>
                <th>Descripción</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $result_productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td>
                        <a href="solicitar_producto.php?producto_id=<?php echo $producto['id']; ?>" class="btn btn-primary">
                            Solicitar
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
