<?php include_once __DIR__ . '/header_dashboard.php'; 
$descripcion = 'Pagina principal del dashboard, se puede observar diversos botones ';
?>

    <?php if(count($proyectos) === 0){?>
        <p class="no_proyectos">Aún no hay proyectos <a href="/crear_proyecto">Crea uno</a></p>
    <?php } else{ ?>
        <ul class="listado_proyectos">
            <?php foreach($proyectos as $proyecto){ ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                        <?php echo $proyecto->proyecto; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php }?>

<?php include_once __DIR__ . '/footer_dashboard.php'; ?>