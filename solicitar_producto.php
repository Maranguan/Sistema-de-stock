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

// Verificar si se ha pasado el producto_id en la URL
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];

    // Obtener los detalles del producto seleccionado
    $sql_producto = "SELECT * FROM productos WHERE id = $producto_id";
    $result_producto = $conn->query($sql_producto);
    $producto = $result_producto->fetch_assoc();

    // Verificar si el producto existe
    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
}

// Si el usuario está haciendo un pedido
if (isset($_POST['hacer_pedido'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $cantidad = $_POST['cantidad'];
    $motivo = $_POST['motivo']; // Obtener el motivo

    // Comprobar si la cantidad pedida es válida
    if ($producto['cantidad'] >= $cantidad) {
        // Insertar solicitud de pedido con el motivo
        $sql_pedido = "INSERT INTO solicitudes (producto_id, cantidad, estado, usuario_id, motivo)
                       VALUES ('$producto_id', '$cantidad', 'Pendiente', '$usuario_id', '$motivo')";
        if ($conn->query($sql_pedido) === TRUE) {
            $success = "Pedido realizado correctamente.";

            // Reducir el stock del producto
            $nuevo_stock = $producto['cantidad'] - $cantidad;
            $sql_update_stock = "UPDATE productos SET cantidad = $nuevo_stock WHERE id = $producto_id";
            $conn->query($sql_update_stock);

            // Redirigir al panel después de hacer el pedido
            header("Location: panel.php");
            exit();
        } else {
            $error = "Error al realizar el pedido.";
        }
    } else {
        $error = "La cantidad solicitada supera la disponibilidad del producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Producto</title>
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
    <h1 class="text-center mb-4">Solicitar Producto</h1>

    <!-- Mensajes de éxito y error -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <h3><?php echo $producto['nombre']; ?></h3>
    <p><strong>Descripción:</strong> <?php echo $producto['descripcion']; ?></p>
    <p><strong>Cantidad Disponible:</strong> <?php echo $producto['cantidad']; ?></p>

    <form method="POST" action="solicitar_producto.php?producto_id=<?php echo $producto['id']; ?>">
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" required min="1" max="<?php echo $producto['cantidad']; ?>">
        </div>
        <!-- Campo para el motivo -->
        <div class="form-group">
            <label for="motivo">Motivo:</label>
            <textarea name="motivo" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" name="hacer_pedido" class="btn btn-success">Realizar Pedido</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
