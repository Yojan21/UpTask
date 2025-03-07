<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboardController{

    public static function index(Router $router){
        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioid', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            //VALIDACION
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //GENERAR UNA URL UNICA
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //ALMACENAR EL CREADOR
                $proyecto->propietarioid = $_SESSION['id'];

                //GUARDAR PROYECTO
                $proyecto->guardar();

                //REDIRECCIONAR
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear_proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        //REVISAR QUE LA PERSONA QUE VISITA ES QUIEN LO CREO
        $token = $_GET['id'];

        if(!$token)header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propietarioid !== $_SESSION['id'])header('Location: /dashboard');

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){
                // VERIFICAR QUE EL EMAIL NO ESTE EN OTRO USUARIO
                $existeUsuario = Usuario::where('email', $usuario->email);
                
                if($existeUsuario && $existeUsuario->id !== $usuario->id ){
                    Usuario::setAlerta('error', 'Ya existe un usuario con el correo digitado');
                }else{
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Los datos fueron actualizados correctamente');
                    //ASIGNAR EL NOMBRE NUEVO A LA BARRA
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);
            
            //SINCRONIZAR CON LOS DATOS DEL USUARIO

            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)){
                $resultado = $usuario->comprobarPassword();

                if($resultado){
                    //ASIGNAR EL NUEVO PASSWORD
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito', 'Contraseña cambiada exitosamente');
                    }

                }else{
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar_password', [
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }
}