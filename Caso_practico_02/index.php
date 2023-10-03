<?php
require_once "Pedido.php";
/*
    $url="http://api.positionstack.com/v1/forward?access_key=521c5d20deb4b33ed5b197c77b3d1ebe";
    echo("Estas consultando esta URL: ".$url."\n");
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $respuesta=curl_exec($ch);
    if(curl_errno($ch)){
        $error_msg=curl_errno(($ch));
        echo("Error al conectarse a la API");
    }else {
        curl_close($ch);

        $data = json_decode($respuesta, true);
    }
*/
$opcion=0;
$array_pedidos=array();
while($opcion!=6) {
    echo "1. Listar todos los pedidos \n
2. Listar pedidos pendientes \n
3. Registrar nuevo pedido \n
4. Recoger pedido \n
5. Entregar pedido \n
6. Salir \n
Escoja una opción: ";
    $opcion = (int)readline("Escoja una opción:");
    if (gettype($opcion) == "integer") {
        switch ($opcion) {
            case 1:
                if(count($array_pedidos)<1){
                    echo("No tienes ningun pedido". "\n");
                    break;
                }else{
                    foreach ($array_pedidos as $pedido) {
                        $pedido->to_string();
                    }
                }
                break;
            case 2:
                if(count($array_pedidos)<1){
                    echo("No tienes ningun pedido pendiente". "\n");
                    break;
                }else{
                    foreach ($array_pedidos as $pedido) {
                        if($pedido->get_estado()=="PENDIENTE"){
                            $pedido->to_string();
                        }
                    }
                }
                break;
            case 3:
                    echo("Escribe un id valido:");
                    $id_pedido = (int)readline("Escribe un id valido:");
                    $existe=false;
                    foreach ($array_pedidos as $pedido) {
                        if ($pedido->get_id() == $id_pedido) {
                            echo("Id en uso" ."\n");
                            $existe=true;
                        }
                    }
                    if($existe){
                        break;
                    }else{
                        echo("Escribe una direccion de recogida:");
                        $dir_recogida = (string)readline("Escribe una direccion de recogida:");
                        echo("Escribe una direccion de entrega:");
                        $dir_entrega = (string)readline("Escribe una direccion de entrega:");
                        $pedido_1 = Pedido::Pedido($id_pedido, $dir_recogida, $dir_entrega);
                        $array_pedidos[] = $pedido_1;

                    break;
                }

            case 4:
                $en_curso=false;
                foreach ($array_pedidos as $pedido) {
                    if($pedido->get_estado()=="RECOGIDO"){
                        $en_curso=true;
                        echo("Pedido en curso: \n");
                        $pedido->to_string();
                    }
                }
                if($en_curso){
                    echo("Tienes uno o varios pedidos en curso, finalizalos para poder gestionar otro pedido");
                }else{
                    echo("Escribe un id valido:");
                    $id_pedido = (int)readline("Escribe un id valido:");
                    $existe=false;
                    $estado_correcto="";
                    foreach ($array_pedidos as $pedido) {
                        if ($pedido->get_id() == $id_pedido) {
                            $existe=true;
                            if($pedido->get_estado()=="PENDIENTE"){
                                $estado_correcto="PENDIENTE";
                                $pedido->set_estado(Estado_pedido::RECOGIDO);
                            }
                        }
                    }
                    if(!$existe){
                        echo("Id no valido \n");
                        break;
                    }
                    if($estado_correcto!="PENDIENTE"){
                        echo("El pedido introducido no se encuentra pendiente \n");
                        break;
                    }
                    echo("Pedido recogido");
                    break;
                }

            case 5:
                echo("Escribe un id valido:");
                $id_pedido = (int)readline("Escribe un id valido:");
                $existe=false;
                $estado_correcto="";
                foreach ($array_pedidos as $pedido) {
                    if ($pedido->get_id() == $id_pedido) {
                        $existe=true;
                        if($pedido->get_estado()=="RECOGIDO"){
                            $estado_correcto="RECOGIDO";
                            $pedido->set_estado(Estado_pedido::ENTREGADO);
                        }
                    }
                }
                if(!$existe){
                    echo("Id no valido \n");
                    break;
                }
                if($estado_correcto!="RECOGIDO"){
                    echo("El pedido introducido no se encuentra recogido \n");
                    break;
                }
                echo("Pedido entregado");
                break;
        }
    }

}

/*
$pedido_1= Pedido::Pedido("1","Ibiza","Madrid");
$pedido_1->set_estado(Estado_pedido::ENTREGADO);
$pedido_1->set_Hora_entrega("2016-11-30 03:55:06");
$pedido_1->set_Hora_recogida("2016-11-30 11:55:06");
$pedido_1->calcular_tiempo();
$pedido_1->to_string();
*/