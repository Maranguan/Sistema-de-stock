<?php
// Incluir la conexión a la base de datos y las funciones
include '../includes/db.php';
include '../includes/funciones.php';
session_start();

// Verificar si el usuario está logueado y es admin
if (!usuario_logueado() || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Agregar producto
if (isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $sql_insertar = "INSERT INTO productos (nombre, cantidad, descripcion) VALUES ('$nombre', '$cantidad', '$descripcion')";
    if ($conn->query($sql_insertar) === TRUE) {
        $success = "Producto agregado exitosamente.";
    } else {
        $error = "Error al agregar el producto.";
    }
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];
    $sql_eliminar = "DELETE FROM productos WHERE id = $id_producto";
    if ($conn->query($sql_eliminar) === TRUE) {
        $success = "Producto eliminado exitosamente.";
    } else {
        $error = "Error al eliminar el producto.";
    }
}

// Editar producto
if (isset($_POST['editar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $sql_editar = "UPDATE productos SET nombre='$nombre', cantidad='$cantidad', descripcion='$descripcion' WHERE id=$id_producto";
    if ($conn->query($sql_editar) === TRUE) {
        $success = "Producto editado exitosamente.";
    } else {
        $error = "Error al editar el producto.";
    }
}

// Obtener los productos
$sql_productos = "SELECT * FROM productos";
$result_productos = $conn->query($sql_productos);

// Verificar si se ha seleccionado un producto para editar
$producto_a_editar = null;
if (isset($_GET['editar'])) {
    $id_producto = $_GET['editar'];
    $sql_producto = "SELECT * FROM productos WHERE id = $id_producto";
    $result_producto = $conn->query($sql_producto);
    $producto_a_editar = $result_producto->fetch_assoc();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Gestionar Productos</h2>

    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>

    <!-- Formulario para agregar un nuevo producto -->
    <form method="POST" action="gestionar_productos.php">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required value="<?php echo $producto_a_editar['nombre'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" required value="<?php echo $producto_a_editar['cantidad'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control" required><?php echo $producto_a_editar['descripcion'] ?? ''; ?></textarea>
        </div>

        <?php if ($producto_a_editar): ?>
            <input type="hidden" name="id_producto" value="<?php echo $producto_a_editar['id']; ?>">
            <button type="submit" name="editar_producto" class="btn btn-warning">Editar Producto</button>
        <?php else: ?>
            <button type="submit" name="agregar_producto" class="btn btn-primary">Agregar Producto</button>
        <?php endif; ?>
    </form>

    <!-- Tabla de productos -->
    <h3 class="mt-5">Productos en Stock</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $result_productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td>
                        <a href="gestionar_productos.php?editar=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="gestionar_productos.php?eliminar=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
