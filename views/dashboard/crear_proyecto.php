<?php include_once __DIR__ . '/header_dashboard.php'; 
$descripcion = 'Interfaz para que el usuario pueda crear proyectos ';
?>

<div class="contenedor_sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form class="formulario" method="POST" action="/crear_proyecto">
        <?php include_once __DIR__ . '/formulario_proyecto.php' ?>
        <input type="submit" value="Crear Proyecto">
    </form>
</div>

<?php include_once __DIR__ . '/footer_dashboard.php'; ?>