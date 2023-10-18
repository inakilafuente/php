<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once '../../lib/Pedido.php';
$host = "172.17.0.3";
$port = 3306;
$user = "root";
$password = "test1234";
$conexion_bd = mysqli_connect($host, $user, $password, 'BD');
if(!$conexion_bd){
    echo 'Error conectando a base de datos: ' . mysqli_connect_error();
    exit;
}
$nuevo_pedido=False;
$res_estados = array();
$res_estados[0]="PENDIENTE";
$res_estados[1]="RECOGIDO";
$res_estados[2]="ENTREGADO";

$array_pedidos_disponibles=array();
$array_riders_disponibles=array();


//print_r($_POST);


if(array_key_exists("id_pedido", $_POST)){
    $id_pedido=$_POST['id_pedido'];
    $ref=$_POST['id'];
    $estado=$_POST['selectEstado'];
    $dir_recog=$_POST['txtDir_recog'];
    $date_recog=$_POST['date_recog'];
    $dir_entreg=$_POST['txtDir_entreg'];
    $date_entreg=$_POST['date_entreg'];
    $tiempo=$_POST['txtTiempo'];
    $dist=$_POST['txtDist'];
    $date_creacion=$_POST['date_crecion'];
    $fk_id_rider=$_POST['fk_idRider'];
}


if(array_key_exists("btn_nuevo_pedido", $_GET)){
    $nuevo_pedido=True;
    $array_ultimo_pedido=array();
    $id_disponible=get_ultimo_pedido($conexion_bd,$array_ultimo_pedido)['PK_id']+1;
    $ref=$_POST['id'];
    $array_pedidos_disponibles=get_pedidos($conexion_bd,$array_pedidos_disponibles);
    $error_ref_existe=false;
    foreach($array_pedidos_disponibles as $pedido){
        if($pedido['Referencia']==$ref){
            $error_ref_existe=true;
            $error_ref_exist_msg="Referencia en uso";
            //echo($ref_exist_msg);
        }
    }
    if(!$error_ref_existe){
        $ref=$_POST['id'];
    }
    $estado=$_POST['selectEstado'];
    $error_estado=false;
    if($estado!="PENDIENTE" && $estado!="RECOGIDO" && $estado!="ENTREGADO"){
        $error_estado=true;
        $error_estado_msg="El estado no es correcto";
        //echo($error_estado_msg);
    }
    if($estado=="PENDIENTE"){
        $estado=0;
    }
    if($estado=="RECOGIDO"){
        $estado=1;
    }
    if($estado=="ENTREGADO"){
        $estado=2;
    }
    $dir_recog=$_POST['txtDir_recog'];
    $date_recog=$_POST['date_recog'];
    $dir_entreg=$_POST['txtDir_entreg'];
    $date_entreg=$_POST['date_entreg'];
    $tiempo=$_POST['txtTiempo'];
    if($date_recog!=null && $date_entreg!=null){
        $tiempo=date_diff($date_recog, $date_entreg);
    }else{
        $tiempo=0;
    }
    $dist=$_POST['txtDist'];
    $date_creacion=$_POST['date_crecion'];
    $fk_id_rider=$_POST['fk_idRider'];
    $array_riders_disponibles=get_riders($conexion_bd,$array_riders_disponibles);
    $error_rider_existe=true;
    foreach($array_riders_disponibles as $rider){
        if($rider['PK_Id']!=$fk_id_rider){
            $error_rider_existe=false;
            $error_rider_existe_msg="Rider no encontrado en el sistema";
            //echo($ref_exist_msg);
        }
    }
    if(!$error_ref_existe){
        $ref=$_POST['id'];
    }

    //$datetime = new DateTime();
    //$newDate = $datetime->createFromFormat('d/m/Y', $date_entreg);

    $pedido=new Pedido($id_disponible,$ref,$dir_recog,$date_recog,$dir_entreg,$date_entreg,$tiempo,$estado,$dist,$date_creacion,$fk_id_rider);
    //echo("---------------- \n");
    //var_dump($pedido);
    //echo("---------------- \n");
    //  guardar_pedido($conexion_bd,$pedido);






}else{
    $array_pedido=array();
    $id_pedido = $_GET['id'];
    $pedido=get_pedido($conexion_bd,$array_pedido,$id_pedido);

    if(empty($id_pedido)){
        echo 'Pedido no encontrado';
        http_response_code(404);
        return;
    }

    //  $_GET[''];
    //guardar_pedido($conexion_bd,$pedido,$array_riders);
}

// Comprobar que exista el pedido recibido...
// ¿Qué hacemos si queremos crear un pedido nuevo?




//$row_pedido = $base_datos->get_pedido($id_pedido);

require_once 'views/ficha.php';


function get_pedido($conexion_bd,$array_pedido,$id_pedido){
    $query="SELECT * FROM PEDIDO WHERE Referencia=".$id_pedido;
    $res_pedido = mysqli_query($conexion_bd, $query);
    if($res_pedido === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_pedido = $res_pedido->fetch_assoc()){
        $array_pedido[]=$row_pedido;
    }
    return $array_pedido;
}

function get_ultimo_pedido($conexion_bd,$array_pedido){
    $query="SELECT PK_id FROM PEDIDO ORDER BY PK_id desc";
    $res_pedido = mysqli_query($conexion_bd, $query);
    if($res_pedido === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_pedido = $res_pedido->fetch_assoc()){
        $array_pedido[]=$row_pedido;
    }
    return $array_pedido[0];
}


function get_riders($conexion_bd,$array_riders){
    $res_riders = mysqli_query($conexion_bd, "SELECT * FROM RIDER");
    if($res_riders === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_rider = $res_riders->fetch_assoc()){
        $array_riders[]=$row_rider;
    }
    return $array_riders;
}
function guardar_pedido($conexion_bd,$pedido){

    $distancia = $pedido->get_dist();
    if ($distancia == "-" || $distancia == "") {
        $distancia = null;
    }
    $hora_recogida = $pedido->get_hora_recogida();
    if ($hora_recogida == "-") {
        $hora_recogida = null;
    }
    $hora_entrega = $pedido->get_hora_entrega();
    if ($hora_entrega == "-") {
        $hora_entrega = null;
    }


        $id_pedido_rider = null;
        $query_desactivar_cheks = "SET FOREIGN_KEY_CHECKS=0";
        mysqli_query($conexion_bd, $query_desactivar_cheks);
        $id_pedido_insert = $pedido->get_id();
        $estado_pedido_insert = $pedido->get_estado();
        $dir_recogida_insert = $pedido->get_dir_recog();
        $dir_entrega_insert = $pedido->get_dir_entrega();
        $tiempo_insert = $pedido->get_Tiempo();
        if ($dir_recogida_insert != null && $dir_entrega_insert != null) {
            $latitud_recogida = 0;
            $longitud_recogida = 0;
            $latitud_entrega = 0;
            $longitud_entrega = 0;
            latitud_longitud($latitud_recogida, $longitud_recogida, $dir_recogida_insert);
            latitud_longitud($latitud_entrega, $longitud_entrega, $dir_entrega_insert);
            $distancia = getDistanceBetweenPointsNew($latitud_recogida, $longitud_recogida, $latitud_entrega, $longitud_entrega, $unit = 'miles');
        } else {
            $distancia = 0;
        }
        $id_rider_insert = $pedido->get_rider();
        $referencia_insert = $pedido->get_Referencia();
        $date_creacion_insert = $pedido->get_Fecha_creacion();

        var_dump($id_pedido_insert);
        var_dump($estado_pedido_insert);
        var_dump($dir_recogida_insert);
        var_dump($hora_recogida);
        var_dump($dir_entrega_insert);
        var_dump($hora_entrega);
        var_dump($tiempo_insert);
        var_dump($distancia);
        var_dump($id_rider_insert);
        var_dump($referencia_insert);
        var_dump($date_creacion_insert);



        $query = "INSERT INTO PEDIDO (PK_id,Estado,Direccion_recogida,Hora_recogida,Direccion_entrega,Hora_entrega,Tiempo_entrega,Distancia,FK_ID_Rider,Referencia,Fecha_creacion)
          VALUES ('$id_pedido_insert','$estado_pedido_insert','$dir_recogida_insert','$hora_recogida', '$dir_entrega_insert','$hora_entrega','$tiempo_insert','$distancia','$id_pedido_rider','$id_rider_insert','$referencia_insert','$date_creacion_insert')";
        $res_insert = mysqli_query($conexion_bd, $query);
        if ($res_insert === false) {
            echo 'Query error: ' . mysqli_error($conexion_bd);
            exit;
        }
        $pk_id_pedido = mysqli_insert_id($conexion_bd);
        if (!$pk_id_pedido) {
            echo 'Error obteniendo PK del pedido insertado';
            exit;
        }
        echo 'Pedido insertado con PK ' . $pk_id_pedido . PHP_EOL;
        $query_activar_cheks = "SET FOREIGN_KEY_CHECKS=1";
        mysqli_query($conexion_bd, $query_activar_cheks);



}
    function get_pedidos($conexion_bd, $array_pedidos){
        $res_pedidos = mysqli_query($conexion_bd, "SELECT * FROM PEDIDO");
        if ($res_pedidos === false) {
            echo 'Query error: ' . mysqli_error($conexion_bd);
            exit;
        }
        while ($row_pedido = $res_pedidos->fetch_assoc()) {
            $array_pedidos[] = $row_pedido;
        }
        return $array_pedidos;
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

