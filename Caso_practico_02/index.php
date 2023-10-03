<?php
require_once "Pedido.php";
/*
    $url="http://api.positionstack.com/v1/forward?access_key=521c5d20deb4b33ed5b197c77b3d1ebe&query=";


    //"1600%20Pennsylvania%20Ave%20NW,%20Washington%20DC"
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $respuesta=curl_exec($ch);
    if(curl_errno($ch)){
        $error_msg=curl_errno(($ch));
        echo("Error al conectarse a la API \n");
    }else {
        curl_close($ch);
        $data = json_decode($respuesta, true);
        foreach ($data as $dir=>$value) {
           $latitud = $value['0']['latitude'];
           $longitud = $value['0']['longitude'];

           echo($latitud. "\n");
           echo($longitud. "\n");
        }
    }
*/
$opcion=0;
$array_pedidos=array();
while($opcion!=7) {
    echo "1. Listar todos los pedidos \n
2. Listar pedidos pendientes \n
3. Registrar nuevo pedido \n
4. Recoger pedido \n
5. Entregar pedido \n
6. Calcular distancia pedido \n
7. Salir \n
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
                        $latitud_recogida=0;
                        $longitud_recogida=0;
                        latitud_longitud($latitud_recogida,$longitud_recogida,$dir_recogida);
                        $latitud_entrega=0;
                        $longitud_entrega=0;
                        latitud_longitud($latitud_entrega,$longitud_entrega,$dir_recogida);
                        $pedido_1 = Pedido::Pedido($id_pedido, $dir_recogida, $dir_entrega);
                        $distancia=getDistanceBetweenPointsNew($latitud_recogida,$longitud_recogida,$latitud_entrega,$longitud_entrega);
                        $pedido_1->set_dist($distancia);
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
                                $pedido->set_Hora_recogida(date("Y-m-d H:i:s"));
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
                    echo("Pedido recogido \n");
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
                            $pedido->set_Hora_entrega(date("Y-m-d H:i:s"));
                            $pedido->calcular_tiempo();
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
                echo("Pedido entregado \n");
                break;
            case 6:
                echo("Escribe un id valido:");
                $id_pedido = (int)readline("Escribe un id valido:");
                $existe=false;
                $estado_correcto="";
                foreach ($array_pedidos as $pedido) {
                    if ($pedido->get_id() == $id_pedido) {
                        $existe=true;
                        $dir_recog=$pedido->get_dir_recog();
                        $latitud_recog=0;
                        $longitud_recog=0;
                        $latitud_entrega=0;
                        $longitud_entrega=0;
                        $dir_entreg=$pedido->get_dir_entreg();
                        latitud_longitud($latitud_recog,$longitud_recog,$dir_recog);
                        latitud_longitud($latitud_entrega,$longitud_entrega,$dir_recog);
                        $distan=getDistanceBetweenPointsNew($latitud_recog,$longitud_recog,$latitud_entrega,$longitud_entrega);
                        echo("La distancia entre las dos direcciones es: ".$distan." km\n");
                        $pedido->set_dist($distan);
                    }
                }
                if(!$existe){
                    echo("Id no valido \n");
                    break;
                }

        }
    }

}


function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'miles') {
    $theta = $longitude1 - $longitude2;
    $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $distance = acos($distance);
    $distance = rad2deg($distance);
    $distance = $distance * 60 * 1.1515;
    switch($unit) {
        case 'miles':
            break;
        case 'kilometers' :
            $distance = $distance * 1.609344;
    }
    return (round($distance,2));
}

function latitud_longitud(&$latitud,&$longitud,$dir){
    $dir_recogida_arr = explode(" ", $dir);
    $recogida_geo = $dir_recogida_arr[0];
    for ($i = 1; $i < count($dir_recogida_arr); $i++) {
        $recogida_geo .= "%20" . $dir_recogida_arr[$i];
    }
    $url = "http://api.positionstack.com/v1/forward?access_key=521c5d20deb4b33ed5b197c77b3d1ebe&query=" . $recogida_geo;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_errno(($ch));
        echo("Error al conectarse a la API \n");
    } else {
        curl_close($ch);
        $data = json_decode($respuesta, true);
        foreach ($data as $dir => $value) {
            $latitud = $value['0']['latitude'];
            $longitud = $value['0']['longitude'];
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