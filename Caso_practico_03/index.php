<?php
require_once "Pedido.php";

$opcion=0;
$array_pedidos=array();
$array_riders=array();

mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT);

$host = "172.17.0.2";
$port = 3306;
$user = "root";
$password = "test1234";

mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT);
$conexion_bd = mysqli_connect($host, $user, $password, 'BD');
if(!$conexion_bd){
    echo 'Error conectando a base de datos: ' . mysqli_connect_error();
    exit;
}






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
Escoja una opción: \n";
    /*
    echo("------RIDERS---------\n");
    print_r($array_riders);
    echo("----------------------\n");
    echo("------PEDIDOS---------\n");
    print_r($array_pedidos);
    echo("----------------------\n");
    */
    $opcion = (int)readline("Escoja una opción:");
    if (gettype($opcion) == "integer") {
        switch ($opcion) {
            case 1:
                get_pedidos($conexion_bd,$array_pedidos);
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
                get_pedidos($conexion_bd,$array_pedidos);
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
                    get_pedidos($conexion_bd,$array_pedidos);
                    get_riders($conexion_bd,$array_riders);
                    echo("Escribe un id valido:");
                    $id_pedido = (int)readline("Escribe un id valido:");
                    $existe=false;
                    foreach ($array_pedidos as $pedido) {
                        if ($pedido['PK_id'] == $id_pedido) {
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
                        echo("Escribe la id del Rider a asignar el pedido: \n");
                        $id_rider=(int)readline("Escribe la id del Rider a asignar el pedido: \n");
                        $Rider=null;
                        foreach ($array_riders as $rid){
                            if($id_rider==$rid['PK_Id']) {
                                $nombre_rider=$rid['nombre'];
                                $apellidos_rider=$rid['apellidos'];
                                $Rider=Rider::Rider($id_rider,$nombre_rider,$apellidos_rider);
                            }
                        }
                        if($Rider==null){
                            echo("El rider introducido no existe");
                            break;
                        }else{
                            var_dump($Rider);
                            $pedido_1 = Pedido::Pedido($id_pedido, $dir_recogida, $dir_entrega,$Rider);
                            $distancia=getDistanceBetweenPointsNew($latitud_recogida,$longitud_recogida,$latitud_entrega,$longitud_entrega);
                            $pedido_1->set_dist($distancia);
                            guardar_pedido($conexion_bd,$pedido_1,$array_riders);
                            $array_pedidos[] = $pedido_1;
                            break;
                        }

                }

            case 4:
                $en_curso=false;
                get_pedidos($conexion_bd,$array_pedidos);
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
                                if($pedido->get_rider()!=null){
                                    echo("No tiene un rider asignado, no se puede recoger \n");
                                    break;
                                }else{
                                    $pedido->set_estado(Estado_pedido::RECOGIDO);
                                    $pedido->set_Hora_recogida(date("Y-m-d H:i:s"));
                                }
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
                get_pedidos($conexion_bd,$array_pedidos);
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
                get_pedidos($conexion_bd,$array_pedidos);
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
                get_riders($conexion_bd,$array_riders);
                $existe=false;
                echo("Escribe el nombre del rider:");
                $nombre_rider = (string)readline("Escribe el nombre del rider:");
                echo("Escribe los apellidos del rider:");
                $apellidos_rider = (string)readline("Escribe los apellidos del rider:");
                foreach ($array_riders as $rid){
                    if ($rid['nombre'] == $nombre_rider && $rid['apellidos'] == $apellidos_rider){
                        $existe=true;
                    }
                }
                if($existe){
                    echo("Estas intentando dar de alta un rider existente \n");
                    break;
                }else{
                    $rider=Rider::Rider(count($array_riders),$nombre_rider,$apellidos_rider);
                    guardar_rider($conexion_bd,$rider);
                    $array_riders[]=$rider;
                    echo("Rider dado de alta \n");
                    break;
                }
            case 8:
                get_riders($conexion_bd,$array_riders);
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
                get_pedidos($conexion_bd,$array_pedidos);
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
function get_pedidos($conexion_bd,&$array_pedidos){
    $res_pedidos = mysqli_query($conexion_bd, "SELECT * FROM PEDIDO");
    if($res_pedidos === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_pedido = $res_pedidos->fetch_assoc()){
        $array_pedidos[]=$row_pedido;
    }
}

function get_riders($conexion_bd,&$array_riders){
    $res_riders = mysqli_query($conexion_bd, "SELECT * FROM RIDER");
    if($res_riders === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_rider = $res_riders->fetch_assoc()){
        $array_riders[]=$row_rider;
    }
}
function guardar_rider($conexion_bd,$rider){
    $id_rider=(int)$rider->get_id();
    $nombre_rider_table=(string)$rider->get_nombre();
    $apellidos_rider_table=(string)$rider->get_apellidos();
    #$query = "INSERT INTO RIDER (PK_id,nombre,apellidos)
     #     VALUES ($id_rider,$nombre_rider_table, $apellidos_rider_table)";
    $query = "INSERT INTO RIDER (PK_id,nombre,apellidos)
          VALUES ('$id_rider','$nombre_rider_table', '$apellidos_rider_table')";
    $res_insert = mysqli_query($conexion_bd, $query);
    if($res_insert === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    $pk_id_pedido = mysqli_insert_id($conexion_bd);
    if(!$pk_id_pedido){
        echo 'Error obteniendo PK del pedido insertado';
        exit;
    }
    echo 'Pedido insertado con PK ' . $pk_id_pedido . PHP_EOL;
}
function guardar_pedido($conexion_bd,$pedido,$array_riders){

    $distancia=$pedido->get_dist();
    if($distancia=="-"){
        $distancia=null;
    }
    $hora_recogida=$pedido->get_hora_recogida();
    if($hora_recogida=="-"){
        $hora_recogida=null;
    }
    $hora_entrega=$pedido->get_hora_entrega();
    if($hora_entrega=="-"){
        $hora_entrega=null;
    }

    $Pedido_Rider=$pedido->get_Rider();
    if($Pedido_Rider==null){
        $id_pedido_rider=null;
    }else{
        $id_pedido_rider=$Pedido_Rider->get_id();
    }
    get_riders($conexion_bd,$array_riders);
    $query_desactivar_cheks="SET FOREIGN_KEY_CHECKS=0";
    mysqli_query($conexion_bd, $query_desactivar_cheks);
    $id_pedido_insert=$pedido->get_id();
    $estado_pedido_insert=$pedido->get_estado();
    $dir_recogida_insert=$pedido->get_dir_recog();
    $dir_entrega_insert=$pedido->get_dir_entrega();
    $tiempo_insert=$pedido->get_Tiempo();
    echo("########################\n");
    echo("########################\n");
    echo("$id_pedido_rider\n");
    echo("########################\n");
    echo("########################\n");
            $query = "INSERT INTO PEDIDO (PK_id,Estado,Direccion_recogida,Hora_recogida,Direccion_entrega,Hora_entrega,Tiempo_entrega,Distancia,FK_ID_Rider)
          VALUES ('$id_pedido_insert','$estado_pedido_insert','$dir_recogida_insert','$hora_recogida', '$dir_entrega_insert','$hora_entrega','$tiempo_insert','$distancia','$id_pedido_rider')";
            $res_insert = mysqli_query($conexion_bd, $query);
            if($res_insert === false){
                echo 'Query error: ' . mysqli_error($conexion_bd);
                exit;
            }
            $pk_id_pedido = mysqli_insert_id($conexion_bd);
            if(!$pk_id_pedido){
                echo 'Error obteniendo PK del pedido insertado';
                exit;
            }
            echo 'Pedido insertado con PK ' . $pk_id_pedido . PHP_EOL;
            $query_activar_cheks="SET FOREIGN_KEY_CHECKS=1";
            mysqli_query($conexion_bd, $query_activar_cheks);



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
