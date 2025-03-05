<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;
use PDO;

class LoginController{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                //VERIFICAR QUE EL USUARIO EXISTA
                $usuario = Usuario::where('email', $usuario->email);
                
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }else{
                    //COMPROBAR EL PASS
                    if(password_verify($_POST['password'], $usuario->password)){
                        //INICIAR LA SESION
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        header('Location: /dashboard');

                    }else{
                        Usuario::setAlerta('error', 'Contraseña Incorrecta');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        //RENDER A LA VISTA
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
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

                    //ENVIAR EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

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
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //BUSCAR EL USUARIO POR EMAIL
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado === "1"){
                    //GENERAR UN NUEVO TOKEN
                    $usuario->generarToken();
                    unset($usuario->password2);
                    //ACTUALIZAR EL USUARIO
                    $usuario->guardar();

                    //ENVIAR EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //IMPRIMIR LA ALERTA
                    Usuario::setAlerta('exito', 'Continua el proceso en tu Email');
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //RENDER A LA VISTA
        $router->render('auth/olvide', [
            'titulo' => 'Restaurar Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token){
            header('Location: /');
        }

        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //AGREGAR EL NUEVO PASS
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                $usuario->hashPassword();

                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header('location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        //RENDER A LA VISTA
        $router->render('auth/reestablecer', [
            'titulo' => 'Restablecer Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router){
        
        //RENDER A LA VISTA
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }

    public static function confirmar(Router $router){
        
        $token = s($_GET['token']);
        if(!$token){
            header('Location: /');
        }

        //Encontrar al usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Valido');
        }else{
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            $usuario->guardar();
            Usuario::setAlerta('exito', 'Hemos comprobado tu cuenta');
        }
        $alertas = Usuario::getAlertas();
        
        //RENDER A LA VISTA
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}