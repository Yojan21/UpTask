<?php include_once __DIR__ . '/header_dashboard.php'; 
$descripcion = 'Pagina para cambiar la contraseña de la cuenta'
?>

<div class="contenedor-sm">
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<a href="/perfil" class="enlace">Volver</a>

<form method="POST" class="formulario" action="/cambiar_password">
    <div class="campo">
        <label for="nombre">Contraseña actual</label>
            <input
                type="password"
                name="password_actual"
                placeholder="Contraseña actual"
            >
    </div>

    <div class="campo">
        <label for="email">Contraseña nueva</label>
            <input
                type="password"
                name="password_nuevo"
                placeholder="Contraseña nueva"
            >
    </div>

    <input type="submit" value="Guardar Cambios">
</form>
</div>

<?php include_once __DIR__ . '/footer_dashboard.php'; ?>