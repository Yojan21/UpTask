<?php 

namespace Model;

class Proyecto extends ActiveRecord{
    
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioid'];

    //CONSTRUCTOR DE LA INSTANCIA
    public function __construct($args=[]){
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioid = $args['propietarioid'] ?? '';
    }

    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El nombre del proyecto es Obligatorio';
        }
        return self::$alertas;
    }
}