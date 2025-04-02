<?php
// Función para verificar si el usuario está logueado
function usuario_logueado() {
    return isset($_SESSION['usuario_id']);
}
?>
