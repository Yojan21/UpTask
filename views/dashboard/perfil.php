<?php include_once __DIR__ . '/header_dashboard.php'; 
$descripcion = 'Interfaz para visualizar la información de la cuenta ';
?>

<div class="contenedor-sm">
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<a href="/cambiar_password" class="enlace">Cambiar contraseña</a>

<form method="POST" class="formulario" action="/perfil">
    <div class="campo">
        <label for="nombre">Nombre</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                value="<?php echo $usuario->nombre; ?>"
                placeholder="Tu Nombre"
            >
    </div>

    <div class="campo">
        <label for="email">Email</label>
            <input
                type="text"
                id="email"
                name="email"
                value="<?php echo $usuario->email; ?>"
                placeholder="Tu Email"
            >
    </div>

    <input type="submit" value="Guardar Cambios">
</form>
</div>

<?php include_once __DIR__ . '/footer_dashboard.php'; ?>