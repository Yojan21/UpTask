<?php

namespace Controllers;

use MVC\Router;
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

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}