<?php
session_start(); // Iniciar sesión si es necesario
include '../includes/header.php'; // Incluir el encabezado con Bootstrap
?>

<div class="container mt-5">
    <h1 class="text-center">Bienvenido al Sistema de Gestión de Stock</h1>

    <!-- Botón para redirigir al login -->
    <div class="text-center mt-4">
        <a href="login.php"><button class="btn btn-primary">Iniciar sesión</button></a>
    </div>
</div>

<?php include '../includes/footer.php'; ?> <!-- Incluir pie de página -->
