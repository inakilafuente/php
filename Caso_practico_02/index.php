<?php
require_once "Pedido.php";

$opcion=0;
$array_pedidos=array();
$array_riders=array();
while($opcion!=9) {
    echo "1. Listar todos los pedidos \n
2. Listar pedidos pendientes \n
3. Registrar nuevo pedido \n
4. Recoger pedido \n
5. Entregar pedido \n
6. Calcular distancia pedido \n
7. Dar alta rider \n
8. Asignar rider a pedido \n
9. Salir \n
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
            case 7:
                $existe=false;
                echo("Escribe el nombre del rider:");
                $nombre_rider = (string)readline("Escribe el nombre del rider:");
                echo("Escribe los apellidos del rider:");
                $apellidos_rider = (string)readline("Escribe los apellidos del rider:");
                foreach ($array_riders as $rid){
                    if ($rid->get_nombre() == $nombre_rider && $rid->get_apellidos() == $apellidos_rider){
                        $existe=true;
                    }
                }
                if($existe){
                    echo("Estas intentando dar de alta un rider existente \n");
                    break;
                }else{
                    $rider=Rider::Rider(count($array_riders),$nombre_rider,$apellidos_rider);
                    $array_riders[]=$rider;
                    echo("Rider dado de alta \n");
                    break;
                }
            case 8:
                $existe_pedido=false;
                $existe_rider=false;
                echo("-----------------------\n");
                echo("Riders dados de alta \n");
                echo("\n");
                foreach ($array_riders as $rid){
                    $rid->to_string();
                }
                echo("-----------------------\n");
                echo("Escribe el id del rider:");
                $id_rider = (int)readline("Escribe el id del rider:");
                echo("Escribe el id del pedido a asignar:");
                $id_pedido = (int)readline("Escribe el id del pedido a asignar:");
                $rider=null;
                foreach ($array_riders as $rid){
                    if($rid->get_id()==$id_rider){
                        $existe_rider=true;
                        $rider=$rid;
                    }
                }
                foreach ($array_pedidos as $pedi){
                    if($pedi->get_id()==$id_pedido && ($pedi->get_rider()==null || $pedi->get_rider()->get_id()!=$rider->get_id())){
                        $existe_pedido=true;
                        $pedi->set_rider($rider);
                    }
                }
                if(!$existe_rider){
                    echo("El rider no existe, has de darle de alta primero \n");
                }
                if(!$existe_pedido){
                    echo("El pedido no existe \n");
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
        if($data['error']!=null){
            echo("Error durante la ejecución de la Api \n");
        }else{
            foreach ($data as $dir => $value) {
                $latitud = $value['0']['latitude'];
                $longitud = $value['0']['longitude'];
            }
        }
    }
}
