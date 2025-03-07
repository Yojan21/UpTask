<aside class="sidebar">
    <div class="contenedor_sidebar">
        <h2><a href="/dashboard">UpTask</a></h2> 
        <div class="cerrar_menu">
            <img id="cerrar_menu" src="build/img/cerrar.svg" alt="Imagen Cerrar Menu">
        </div>
    </div>
    
    <nav class="sidebar_nav">
        <a class="<?php echo($titulo === 'Proyectos') ? 'activo' : '' ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo($titulo === 'Crear Proyecto') ? 'activo' : '' ?>" href="/crear_proyecto">Crear Proyecto</a>
        <a class="<?php echo($titulo === 'Perfil') ? 'activo' : '' ?>" href="/perfil">Perfil</a>
    </nav>

    <div class="cerrar_sesion_mobile">
        <a href="/logout" class="cerrar_sesion">Cerrar Sesión</a>
    </div>
</aside>