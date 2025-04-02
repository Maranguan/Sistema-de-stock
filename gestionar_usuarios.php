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

// Agregar usuario
if (isset($_POST['agregar_usuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo']; // Cambiar 'email' por 'correo'
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Contraseña encriptada
    $rol = $_POST['rol'];

    // Cambiar 'password' por 'contrasena' en la consulta
    $sql_insertar = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES ('$nombre', '$correo', '$password', '$rol')";
    if ($conn->query($sql_insertar) === TRUE) {
        $success = "Usuario agregado exitosamente.";
    } else {
        $error = "Error al agregar el usuario.";
    }
}

// Editar usuario
if (isset($_POST['editar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo']; // Cambiar 'email' por 'correo'
    // Cambiar 'password' por 'contrasena' en la consulta y verificar si la contraseña se actualiza
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $_POST['password_original']; // Si no se cambia la contraseña, se mantiene la original
    $rol = $_POST['rol'];

    // Cambiar 'password' por 'contrasena' en la consulta
    $sql_editar = "UPDATE usuarios SET nombre='$nombre', correo='$correo', contrasena='$password', rol='$rol' WHERE id=$id_usuario";
    if ($conn->query($sql_editar) === TRUE) {
        $success = "Usuario editado exitosamente.";
    } else {
        $error = "Error al editar el usuario.";
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id_usuario = $_GET['eliminar'];
    $sql_eliminar = "DELETE FROM usuarios WHERE id = $id_usuario";
    if ($conn->query($sql_eliminar) === TRUE) {
        $success = "Usuario eliminado exitosamente.";
    } else {
        $error = "Error al eliminar el usuario.";
    }
}

// Obtener los usuarios
$sql_usuarios = "SELECT * FROM usuarios";
$result_usuarios = $conn->query($sql_usuarios);

// Verificar si se ha seleccionado un usuario para editar
$usuario_a_editar = null;
if (isset($_GET['editar'])) {
    $id_usuario = $_GET['editar'];
    $sql_usuario = "SELECT * FROM usuarios WHERE id = $id_usuario";
    $result_usuario = $conn->query($sql_usuario);
    $usuario_a_editar = $result_usuario->fetch_assoc();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Gestionar Usuarios</h2>

    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>

    <!-- Formulario para agregar un nuevo usuario -->
    <form method="POST" action="gestionar_usuarios.php">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required value="<?php echo $usuario_a_editar['nombre'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label for="correo">Correo:</label> <!-- Cambiar 'Email' a 'Correo' -->
            <input type="email" name="correo" class="form-control" required value="<?php echo $usuario_a_editar['correo'] ?? ''; ?>"> <!-- Cambiar 'email' a 'correo' -->
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" class="form-control" <?php echo $usuario_a_editar ? '' : 'required'; ?>>
            <?php if ($usuario_a_editar): ?>
                <input type="hidden" name="password_original" value="<?php echo $usuario_a_editar['contrasena']; ?>"> <!-- Cambiar 'password' a 'contrasena' -->
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="rol">Rol:</label>
            <select name="rol" class="form-control" required>
                <option value="admin" <?php echo ($usuario_a_editar && $usuario_a_editar['rol'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($usuario_a_editar && $usuario_a_editar['rol'] == 'user') ? 'selected' : ''; ?>>Usuario</option>
            </select>
        </div>

        <?php if ($usuario_a_editar): ?>
            <input type="hidden" name="id_usuario" value="<?php echo $usuario_a_editar['id']; ?>">
            <button type="submit" name="editar_usuario" class="btn btn-warning">Editar Usuario</button>
        <?php else: ?>
            <button type="submit" name="agregar_usuario" class="btn btn-primary">Agregar Usuario</button>
        <?php endif; ?>
    </form>

    <!-- Tabla de usuarios -->
    <h3 class="mt-5">Usuarios Registrados</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th> <!-- Cambiar 'Email' por 'Correo' -->
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['correo']; ?></td> <!-- Cambiar 'email' por 'correo' -->
                    <td><?php echo $usuario['rol']; ?></td>
                    <td>
                        <a href="gestionar_usuarios.php?editar=<?php echo $usuario['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="gestionar_usuarios.php?eliminar=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
