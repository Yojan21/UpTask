<?php

namespace Model;

class Usuario extends ActiveRecord{

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    //CONSTRUCTOR DE LA INSTANCIA
    public function __construct($args=[]){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //VALIDACION PARA CUENTAS NUEVAS->GENERACION DE ALERTAS
    public function validadNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del Usuario es obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'La Contraseña del Usuario es obligatoria';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La Contraseña debe tener por lo menos 6 caracteres';
        }

        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'La Contraseñas no coinciden';
        }

        return self::$alertas;
    }

    //HASEA EL PASSWORD
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }   

    //GENERAR EL TOKEN
    public function generarToken(){
        $this->token = uniqid();
    }
}