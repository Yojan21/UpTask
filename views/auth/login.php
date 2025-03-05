<div class="contenedor login">
<?php include_once __DIR__ . '/../templates/nombre_sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion_pagina">Iniciar Sesion</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form method="POST" action="/" class="formulario" novalidate>

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                    >
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Contraseña"
                    name="password"
                    >
            </div>

            <input type="submit" class="boton" value="Iniciar Sesion">
        </form>

        <div class="acciones">
            <a href="/crear">Aún no tienes una cuenta? Crea Una</a>
            <a href="/olvide">Olvidaste tu contraseña</a>
        </div>
    </div> <!-- Cierre de contenedor-sm -->
</div>