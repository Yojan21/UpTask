(function(){
    //BOTON PARA MOSTRAR EL MODAL DE AGREGAR TAREA
    const nuevaTareaBtn = document.querySelector('#agregar_tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario(){
        const modal =  document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva_tarea">
                <legend>Agrega una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        id="tarea"
                        placeholder="Agregar tarea al proyecto actual"
                    />
                </div>
                <div class="opciones">
                    <input 
                        type="submit" 
                        class="submit_nueva_tarea"
                        value="Agregar Tarea"
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
                submitFormularioNuevaTarea();
            }

            
        });


        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();
        
        if(tarea === ''){
            //MOSTRAR UNA ALERTA
            mostrarAlerta('El nombre de la tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
            return;
        }
        agregarTarea(tarea);
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
            }

            
        } catch (error) {
            console.log('Error');
        }
    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
})();