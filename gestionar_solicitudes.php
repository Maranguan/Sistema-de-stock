<?php
// Incluir la conexión a la base de datos
include '../includes/db.php';
include '../includes/funciones.php';
session_start();

// Verificar si el usuario está logueado y es admin
if (!usuario_logueado() || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener todas las solicitudes con los nombres de los productos y usuarios
$sql_solicitudes = "SELECT s.*, p.nombre AS nombre_producto, u.nombre AS nombre_usuario
                    FROM solicitudes s
                    JOIN productos p ON s.producto_id = p.id
                    JOIN usuarios u ON s.usuario_id = u.id";
$result_solicitudes = $conn->query($sql_solicitudes);

// Aprobar, rechazar o finalizar solicitud
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_solicitud = $_GET['id'];
    $accion = $_GET['accion'];

    // Comprobamos la acción para actualizar el estado
    if ($accion == 'aprobar') {
        // Cambiar el estado a 'en curso' cuando se aprueba
        $sql_actualizar = "UPDATE solicitudes SET estado = 'en curso' WHERE id = $id_solicitud";
    } elseif ($accion == 'rechazar') {
        // Cambiar el estado a 'denegado' cuando se rechaza
        $sql_actualizar = "UPDATE solicitudes SET estado = 'denegado' WHERE id = $id_solicitud";
    } elseif ($accion == 'finalizar') {
        // Cambiar el estado a 'finalizado' cuando se finaliza
        $sql_actualizar = "UPDATE solicitudes SET estado = 'finalizado' WHERE id = $id_solicitud";
    }

    // Ejecutar la consulta de actualización
    if ($conn->query($sql_actualizar) === TRUE) {
        header("Location: gestionar_solicitudes.php");
        exit();
    } else {
        // En caso de error, mostramos un mensaje
        $error = "Error al actualizar el estado de la solicitud.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Gestionar Solicitudes</h2>

    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <!-- Tabla de solicitudes -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Solicitante</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($solicitud = $result_solicitudes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $solicitud['nombre_producto']; ?></td>
                    <td><?php echo $solicitud['cantidad']; ?></td>
                    <td><?php echo $solicitud['motivo']; ?></td>
                    <td><?php echo $solicitud['estado']; ?></td>
                    <td><?php echo $solicitud['nombre_usuario']; ?></td>
                    <td>
                        <?php if ($solicitud['estado'] == 'en curso'): ?>
                            <a href="gestionar_solicitudes.php?accion=finalizar&id=<?php echo $solicitud['id']; ?>" class="btn btn-success btn-sm">Finalizar</a>
                        <?php else: ?>
                            <a href="gestionar_solicitudes.php?accion=aprobar&id=<?php echo $solicitud['id']; ?>" class="btn btn-success btn-sm">Aprobar</a>
                        <?php endif; ?>
                        <a href="gestionar_solicitudes.php?accion=rechazar&id=<?php echo $solicitud['id']; ?>" class="btn btn-danger btn-sm">Rechazar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
