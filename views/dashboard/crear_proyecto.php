<?php include_once __DIR__ . '/header_dashboard.php'; ?>

<div class="contenedor_sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form class="formulario">
        <?php include_once __DIR__ . '/formulario_proyecto.php' ?>
        <input type="submit" value="Crear Proyecto">
    </form>
</div>

<?php include_once __DIR__ . '/footer_dashboard.php'; ?>