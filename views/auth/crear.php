<div class="contenedor crear">
    
<?php include_once __DIR__ . '/../templates/nombre_sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion_pagina">Crea tu cuenta en UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form method="POST" action="/crear" class="formulario">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>">
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="text"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                    value="<?php echo $usuario->email; ?>">
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

            <div class="campo">
                <label for="password2">Repite tu Contraseña</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite tu Contraseña"
                    name="password2"
                    >
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>
        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/olvide">Olvidaste tu contraseña</a>
        </div>
    </div> <!-- Cierre de contenedor-sm -->
</div>