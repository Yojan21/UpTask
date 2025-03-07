(function(){

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    //BOTON PARA MOSTRAR EL MODAL DE AGREGAR TAREA
    const nuevaTareaBtn = document.querySelector('#agregar_tarea');
    nuevaTareaBtn.addEventListener('click', function(){
        mostrarFormulario(false);
    });

    //FILTROS DE BUSQUEDA
    const filtros = document.querySelectorAll('.filtros input[type="radio"]');

    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    }
    )

    function filtrarTareas(e){
        const filtro = e.target.value;
        if(filtro !== ''){
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        }else{
            filtradas = [];

        }

        mostrarTareas();
    }

    async function obtenerTareas(){
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;

            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas(){
        limpiarTareas();
        totalPendientes();
        totalCompletas();
        const arrayTareas = filtradas.length ? filtradas : tareas;
        if(arrayTareas.length === 0){ //Si no hay tareas
            const contenedorTareas = document.querySelector('#listado_tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no_tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }
        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaid = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
            nombreTarea.ondblclick = function (){
                mostrarFormulario(true, {...tarea});
            }

            //Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado_tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadotarea = tarea.estado;

            btnEstadoTarea.ondblclick = function(){
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar_tarea');
            btnEliminarTarea.dataset.isTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';

            btnEliminarTarea.ondblclick = function(){
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listado_tareas = document.querySelector('#listado_tareas');
            listado_tareas.appendChild(contenedorTarea);
        });
        
    }

    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalPendientes.length === 0){
            pendientesRadio.disabled = true
        }else{
            pendientesRadio.disabled = false
        }
    }

    function totalCompletas(){
        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        const completasRadio = document.querySelector('#completadas');

        if(totalCompletas.length === 0){
            completasRadio.disabled = true
        }else{
            completasRadio.disabled = false
        }
    }

    function mostrarFormulario(editar = false, tarea = {}){
        const modal =  document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva_tarea">
                <legend>${editar ? 'Editar Tarea' : 'Agrega una nueva tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        id="tarea"
                        placeholder="${tarea.nombre ? 'Edita la tarea' : ' Agregar tarea al proyecto actual'}"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>
                <div class="opciones">
                    <input 
                        type="submit" 
                        class="submit_nueva_tarea"
                        value="${tarea.nombre ? 'Editar Tarea' : 'Agregar Tarea'}"
                    />
                    <button type="button" class="cerrar_modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(()=>{
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();
            if(e.target.classList.contains('cerrar_modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(()=>{
                    modal.remove();
                }, 500);
                
            }

            if(e.target.classList.contains('submit_nueva_tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim();
        
                if(nombreTarea === ''){
                    //MOSTRAR UNA ALERTA
                    mostrarAlerta('El nombre de la tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
            } 
        });
        document.querySelector('.dashboard').appendChild(modal);
    }

    function mostrarAlerta(mensaje, tipo, referencia){
    //PREVIENE LA CREACION DE MULTIPLES ALERTAS
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        const alerta = document.createElement('div');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //ELIMINAR LA ALERTA DESPUES DE 3 SEG
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

    //Agrega la nueva tarea al proyecto actual
    async function agregarTarea(tarea){
        //CONSTRUIR LA PETICION
        const datos = new FormData();

        datos.append('nombre', tarea);
        datos.append('proyectoid', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);

                //Agregar el objeto d etarea al global de tareas
                const tareasObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoid: resultado.proyectoid
                }

                tareas = [...tareas, tareasObj];
                mostrarTareas();
            }

            
        } catch (error) {
            console.log('Error');
        }
    }

    function cambiarEstadoTarea(tarea){
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea){
        
        const {estado, id, nombre, proyectoid} = tarea;
        const datos = new FormData();

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerProyecto());

        /* for(let valor of datos.values()){
            console.log(valor);
        } */

            try {
                const url = 'http://localhost:3000/api/tarea/actualizar';
                const respuesta = await fetch(url, {
                    'method': 'POST',
                    'body': datos
                });
                const resultado = await respuesta.json();
                if(resultado.respuesta.tipo === 'exito'){
                    console.log(resultado.respuesta.tipo);
                    Swal.fire('Actualizado!', resultado.respuesta.mensaje, 'success');
                }

                const modal = document.querySelector('.modal');
                if(modal){
                    modal.remove();
                }
                
                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });

                mostrarTareas();

            } catch (error) {
                console.log(error)
            }

    }

    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: "Eliminar tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }

    async function eliminarTarea(tarea){
        
        const {estado, id, nombre} = tarea;
        const datos = new FormData();

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerProyecto());
        
        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            if(resultado.resultado){
                Swal.fire('Eliminado!', resultado.mensaje, 'success');
            }
            
            tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id)
            mostrarTareas();
            
        } catch (error) {
            
        }
    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    function limpiarTareas(){
        const listado_tareas = document.querySelector('#listado_tareas');
        while(listado_tareas.firstChild){
            listado_tareas.removeChild(listado_tareas.firstChild);
        }
    }
})();