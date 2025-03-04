<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombre_sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion_pagina">Restaura tu Contraseña</p>

        <form method="POST" action="/olvide" class="formulario">

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                    >
            </div>

            <input type="submit" class="boton" value="Restaurar Contraseña">
        </form>

        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/crear">Aún no tienes una cuenta? Crea Una</a>
        </div>
    </div> <!-- Cierre de contenedor-sm -->
</div>