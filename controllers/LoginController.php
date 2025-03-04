<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){


        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
        }

        //RENDER A LA VISTA
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion'
        ]);
    }

    public static function logout(){
        echo 'Desde logout';
    }

    public static function crear(Router $router){

        //INSTACIAR CON EL MODELO USUARIO, ENTRA AL CONSTRUCTOR Y CREA UN OBJETO VACIO
        $alertas = [];
        $usuario = new Usuario;
        
    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST); //METODO DE ACTIVE RECORD
            $alertas = $usuario->validadNuevaCuenta();

            if(empty($alertas)){
                //BUSCAR USUARIOS POR EMAIL PARA EVITAR DUPLICADOS
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                }else{
                    //HASHEAR EL PASS
                    $usuario->hashPassword();
                    
                    //ELIMINAR PASSWORD2
                    unset($usuario->password2);

                    //GENERAR EL TOKEN
                    $usuario->generarToken();

                    //CREAR UN NUEVO USUARIO
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }

                    debuguear($usuario);
                }
            }
        }

        //RENDER A LA VISTA
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        //RENDER A LA VISTA
        $router->render('auth/olvide', [
            'titulo' => 'Restaurar Contraseña'
        ]);
    }

    public static function restablecer(Router $router){
        

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        //RENDER A LA VISTA
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Contraseña'
        ]);
    }

    public static function mensaje(Router $router){
        
        //RENDER A LA VISTA
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }

    public static function confirmar(Router $router){
        
        
        //RENDER A LA VISTA
        $router->render('auth/confirmar', [
            'titulo' => 'Restablecer Contraseña'
        ]);
    }
}