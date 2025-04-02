<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Stock</title>

    <!-- Incluir Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Incluir jQuery (para el funcionamiento de algunos componentes de Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Incluir Popper.js (requerido para algunos componentes de Bootstrap como modales) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>

    <!-- Incluir Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Estilos adicionales -->
    <link rel="stylesheet" href="assets/style.css"> <!-- Si tienes tu propio estilo CSS -->
</head>
<body>

<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Gestor de Stock</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="panel.php">Panel de Control</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="gestionar_productos.php">Gestionar Productos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="gestionar_usuarios.php">Gestionar Usuarios</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ver_solicitudes.php">Ver Solicitudes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="gestionar_solicitudes.php">Gestionar Solicitudes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>
