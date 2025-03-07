
const mobileMenuBtn = document.querySelector('#mobile_menu');
const sidebar = document.querySelector('.sidebar');
const cerrarMenuBtn = document.querySelector('#cerrar_menu');

if(mobileMenuBtn){
    mobileMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('mostrar');
    });
}

if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click', function(){
        
        sidebar.classList.add('ocultar');

        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 1000);
    });
}

//ELIMINA LA CLASE DE MOSTRAR EN TABLET O MAYOR
const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function(){
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768){
        sidebar.classList.remove('mostrar')
    }
});