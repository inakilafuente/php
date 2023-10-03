<?php
require_once "Estado_pedido.php";
class Pedido{
    private $id;
    private $estado_pedido;
    private $Direccion_recogida;
    private $Hora_recogida;
    private $Direccion_entrega;
    private $Hora_entrega;
    private $Tiempo_entrega;

    public function __construct($id, $Direccion_recogida, $Hora_recogida,$Direccion_entrega,$Hora_entrega,$Tiempo_entrega,$estado_pedido)
    {
        $this->id = $id;
        $this->estado_pedido = $estado_pedido;
        $this->Direccion_recogida = $Direccion_recogida;
        $this->Hora_recogida = $Hora_recogida;
        $this->Direccion_entrega=$Direccion_entrega;
        $this->Hora_entrega=$Hora_entrega;
        $this->Tiempo_entrega=$Tiempo_entrega;
    }
    public static function Pedido($id, $Direccion_recogida, $Direccion_entrega){
        return new self($id, $Direccion_recogida, "-", $Direccion_entrega, "-","-",Estado_pedido::PENDIENTE);
    }
    public function get_id(){
        return $this->id;
    }

    public function get_estado(){
        if ($this->estado_pedido==1) {
            return 'PENDIENTE';
        }elseif ($this->estado_pedido==2) {
            return 'RECOGIDO';
        }elseif ($this->estado_pedido==3) {
            return 'ENTREGADO';
        }
    }

    public function set_estado($estado_actual){
        $this->estado_pedido=$estado_actual;
    }

    public function set_Hora_recogida($hora_recogida){
        $this->Hora_recogida=$hora_recogida;
    }
    public function set_Hora_entrega($hora_entrega){
        $this->Hora_entrega=$hora_entrega;
    }
    public function calcular_tiempo(){
        if($this->Hora_entrega!="-" && $this->Hora_recogida!="-"){
            $this->Tiempo_entrega=strtotime($this->Hora_recogida) - strtotime($this->Hora_entrega);

        }
    }
    public function to_string(){
        echo("Pedido ". $this->id ."\n".
            "\t" ."Estado: ".$this->get_estado() ."\n".
            "\t" ."Dirección de recogida: ".$this->Direccion_recogida. "\n".
            "\t" ."Hora de recogida: ".$this->Hora_recogida. "\n".
            "\t" ."Dirección de entrega: ".$this->Direccion_entrega. "\n".
            "\t" ."Hora de entrega: ".$this->Hora_entrega ."\n".
            "\t" ."Tiempo de entrega: ");
            if($this->Tiempo_entrega!="-"){
                $hours = floor($this->Tiempo_entrega / 3600);
                $minutes = floor(($this->Tiempo_entrega / 60) % 60);
                $seconds = $this->Tiempo_entrega % 60;
                echo($hours."horas ".$minutes."minutos ".$seconds."segundos \n");
            }
            else{
                echo($this->Tiempo_entrega. "\n");
            }

    }

}