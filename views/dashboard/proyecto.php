<?php include_once __DIR__ . '/header_dashboard.php'; 
$descripcion = 'Interfaz para visualizar las tareas de determinado proyecto ';
?>

<div class="contenedor_sm">
    <div class="contenedor_nueva_tarea">
        <button
            type="button"
            class="agregar_tarea"
            id="agregar_tarea"
        >&#43; Nueva Tarea</button>
    </div>

    <div class="filtros" id="filtros">
        <div class="filtros_inputs">
            <h2>Filtros:</h2>
            <div class="campo">
                <label for="todas">Todas</label>
                <input 
                    type="radio"
                    id="todas"
                    name="filtro"
                    value=""
                    checked
                >
            </div>

            <div class="campo">
                <label for="todas">Completadas</label>
                <input 
                    type="radio"
                    id="completadas"
                    name="filtro"
                    value="1"
                >
            </div>

            <div class="campo">
                <label for="todas">Pendientes</label>
                <input 
                    type="radio"
                    id="pendientes"
                    name="filtro"
                    value="0"
                >
            </div>
        </div>
    </div>

    <ul id="listado_tareas" class="listado_tareas">

    </ul>
</div>

<?php include_once __DIR__ . '/footer_dashboard.php'; ?>

<?php $script .= '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/tareas.js"></script>'; 
    ?>