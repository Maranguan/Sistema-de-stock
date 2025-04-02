<?php
// Incluir la conexión a la base de datos
include '../includes/db.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si el usuario es administrador
$is_admin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';

// Obtener las solicitudes del usuario o todas si es admin
if ($is_admin) {
    // Si es admin, mostrar todas las solicitudes
    $sql_solicitudes = "SELECT s.*, u.nombre AS nombre_usuario, p.nombre AS nombre_producto
                        FROM solicitudes s
                        JOIN usuarios u ON s.usuario_id = u.id
                        JOIN productos p ON s.producto_id = p.id";
} else {
    // Si no es admin, solo mostrar las solicitudes del usuario logueado
    $usuario_id = $_SESSION['usuario_id'];
    $sql_solicitudes = "SELECT s.*, u.nombre AS nombre_usuario, p.nombre AS nombre_producto
                        FROM solicitudes s
                        JOIN usuarios u ON s.usuario_id = u.id
                        JOIN productos p ON s.producto_id = p.id
                        WHERE s.usuario_id = $usuario_id";
}

$result_solicitudes = $conn->query($sql_solicitudes);
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Solicitudes</h2>

    <!-- Mostrar solicitudes -->
    <table class="table table-striped">
        <thead>
            <tr>
                <?php if ($is_admin): ?>
                    <th>Usuario</th>
                <?php endif; ?>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($solicitud = $result_solicitudes->fetch_assoc()): ?>
                <tr>
                    <?php if ($is_admin): ?>
                        <td><?php echo $solicitud['nombre_usuario']; ?></td>
                    <?php endif; ?>
                    <td><?php echo $solicitud['nombre_producto']; ?></td>
                    <td><?php echo $solicitud['cantidad']; ?></td>
                    <td><?php echo $solicitud['motivo']; ?></td>
                    <td><?php echo $solicitud['estado']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
