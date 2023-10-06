<?php
class Rider{
    private $id;
    private $nombre;
    private $apellidos;

    public function __construct($id, $nombre, $apellidos)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
    }
    public static function Rider($id, $nombre, $apellidos){
        return new self($id, $nombre, $apellidos);
    }
    public function get_id(){
        return $this->id;
    }
    public function get_nombre(){
        return $this->nombre;
    }
    public function set_nombre($nombre){
        $this->nombre=$nombre;
    }
    public function get_apellidos(){
        return $this->apellidos;
    }
    public function set_apellidos($apellidos){
        $this->apellidos=$apellidos;
    }
    public function to_string(){
        echo("Rider: \n".
            "\tID: ".$this->id."\n".
            "\tNombre: ".$this->nombre."\n".
            "\tApellidos: ".$this->apellidos ."\n");
    }
}