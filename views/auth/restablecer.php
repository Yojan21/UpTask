<div class="contenedor restablecer">
<?php include_once __DIR__ . '/../templates/nombre_sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion_pagina">Coloca tu nueva Contraseña</p>

        <form method="POST" action="/reestablecer" class="formulario">

            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Contraseña"
                    name="password"
                    >
            </div>

            <input type="submit" class="boton" value="Guardar Contraseña">
        </form>

        <div class="acciones">
            <a href="/crear">Aún no tienes una cuenta? Crea Una</a>
            <a href="/olvide">Olvidaste tu contraseña</a>
        </div>
    </div> <!-- Cierre de contenedor-sm -->
</div>