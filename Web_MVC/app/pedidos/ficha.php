<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once '../../lib/Pedido.php';
error_reporting(E_ERROR | E_PARSE);
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



$array_pedido=array();
$array_pedidos_disponibles=array();
$array_riders_disponibles=array();
$array_riders_disponibles_asignar=array();
$pedido=null;
$existe_pedido_id=false;
//print_r($_POST);
if(empty($tiempo)){
    $tiempo="-";
}
/*
if(empty($dist)){
    $dist="-";
}
*/
$error_ref_existe=false;
$error_estado=false;

$puede_recoger=false;
$puede_entregar=false;
$pedido_existe =false;
if(array_key_exists("id", $_POST)){
    $id_pedido=$_POST['id_pedido'];
    $ref=addslashes($_POST['id']);
    $estado=$_POST['selectEstado'];
    $dir_recog=addslashes($_POST['txtDir_recog']);
    $date_recog = strtotime($_POST["date_recog"]);
    $dir_entreg=addslashes($_POST['txtDir_entreg']);
    $date_entreg=strtotime($_POST["date_entreg"]);
    $tiempo=$_POST['txtTiempo'];
    //$dist=$_POST['txtDist'];
    //echo($dir_recog."##");
    //echo($dir_entreg."##");
    if($dir_recog!="" && $dir_entreg!=""){
        latitud_longitud($latitud_recog,$longitud_recog,$dir_recog);
        latitud_longitud($latitud_entreg,$longitud_entreg,$dir_entreg);
        $dist = getDistanceBetweenPointsNew($latitud_recog, $longitud_recog, $latitud_entreg, $longitud_entreg, $unit = 'miles');
        //echo($dist);
    }
    $date_creacion=strtotime($_POST["date_crecion"]);
    $fk_id_rider=addslashes($_POST['fk_idRider']);

    $array_pedidos_disponibles=get_pedidos($conexion_bd,$array_pedidos_disponibles);


    foreach($array_pedidos_disponibles as $pedido_foreach){
        if($pedido_foreach['Referencia']==$ref){
    if($id_pedido==get_pedido_ref($conexion_bd,$array_pedido,$ref)[0]['PK_id']){
            $pedido_existe =true;
            $error_ref_existe=true;
            $error_ref_exist_msg="Referencia en uso";
    }
        }
    }

    if($ref==""){
        $error_ref_vacia=true;
        $error_ref_vacia_msg="Referencia vacia";
    }
    if($estado!="PENDIENTE" && $estado!="RECOGIDO" && $estado!="ENTREGADO"){
        $error_estado=true;
        $error_estado_msg="El estado no es correcto";
        //echo($error_estado_msg);
    }

    $pedido_por_id=get_pedido_por_id($conexion_bd,$array_pedido,$id_pedido);
    if(!empty($pedido_por_id[0])){
        $existe_pedido_id=true;
    }
}


if(array_key_exists("btn_nuevo_pedido", $_GET)){

    $nuevo_pedido=True;
    $array_ultimo_pedido=array();
    $id_disponible=get_ultimo_pedido($conexion_bd,$array_ultimo_pedido)['PK_id']+1;
    $date_creacion=date('Y-m-d\TH:i');
    $tiempo="-";
    //$dist="-";

}elseif (array_key_exists('id', $_GET)){
        $array_pedido=array();
        $pedidos=array();
        $id_pedido = addslashes($_GET['id']);
            $pedidos=get_pedidos($conexion_bd,$pedidos);
            $existe=false;
            foreach($pedidos as $ped){
                if($ped['Referencia']==$id_pedido){
                    $existe=true;
                }
            }
            $pedido=get_pedido($conexion_bd,$array_pedido,$id_pedido);
            //var_dump($pedido);
            if(empty($id_pedido) || !$existe){
                echo 'Pedido no encontrado';
                http_response_code(404);
                return;
            }
            $date_creacion=strtotime($pedido[0]['Fecha_creacion']);
            if($fk_id_rider!=""&&$fk_id_rider>0&&$fk_id_rider!=null){
                $pedidos_por_rider=get_pedidos_por_rider($conexion_bd,$array_pedido,$fk_id_rider);
                $error_rider_ocupado=false;
                foreach($pedidos_por_rider as $pedido_por_rider){
                    if($pedido_por_rider["Estado"]>0){
                        $error_rider_ocupado=true;
                        $error_rider_ocupado_msg="Rider ocupado";
                    }
                }
            }

            $dir_recog=$pedido[0]["Direccion_recogida"];
            $dir_entreg=$pedido[0]["Direccion_entrega"];
            if($dir_recog!="" && $dir_entreg!=""){
                latitud_longitud($latitud_recog,$longitud_recog,$dir_recog);
                latitud_longitud($latitud_entreg,$longitud_entreg,$dir_entreg);
                $dist = getDistanceBetweenPointsNew($latitud_recog, $longitud_recog, $latitud_entreg, $longitud_entreg, $unit = 'miles');
                //echo($dist);
            }
            $estado=$pedido[0]["Estado"];
                //echo(!$error_rider_ocupado. " - ".$dir_recog." - ".$estado. "\n");
                if(!$error_rider_ocupado && $dir_recog!=" " && $estado=="0"){
                    $puede_recoger=true;
                }
                if(!$error_rider_ocupado && $dir_entreg!=" " && $estado=="1"){
                    $puede_entregar=true;
                }
    $array_riders_disponibles_asignar=get_riders_disponibles($conexion_bd,$array_riders_disponibles_asignar);

    }  else{
    if(array_key_exists('id', $_POST)) {
        $ref=addslashes($_POST['id']);
        $array_pedido=array();
        $array_pedidos_disponibles=get_pedidos($conexion_bd,$array_pedidos_disponibles);
        $error_ref_existe=false;
        foreach($array_pedidos_disponibles as $pedido_buscar){
            if($pedido_buscar['Referencia']==$ref){
                if($id_pedido==get_pedido_ref($conexion_bd,$array_pedido,$ref)[0]['PK_id']){
                    $pedido_existe =true;
                    $error_ref_existe=true;
                    $error_ref_exist_msg="Referencia en uso";
                    //echo($ref_exist_msg);
                    }
            }
        }
        if($pedido['Referencia']==" "){
            $error_ref_vacia=true;
            $error_ref_vacia_msg="Referencia vacia";
        }
        if(!$error_ref_existe){
            $ref=addslashes($_POST['id']);
        }
        $estado=$_POST['selectEstado'];

        if($estado!="PENDIENTE" && $estado!="RECOGIDO" && $estado!="ENTREGADO"){
            $error_estado=true;
            $error_estado_msg="El estado no es correcto";
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
        $dir_recog=addslashes($_POST['txtDir_recog']);
        $date_recog=$_POST['date_recog'];
        $dir_entreg=addslashes($_POST['txtDir_entreg']);
        $date_entreg=$_POST['date_entreg'];
        $tiempo=$_POST['txtTiempo'];
        if($date_recog!=null && $date_entreg!=null){
            $tiempo=(strtotime($date_entreg)-strtotime($date_recog));
        }else{
            $tiempo=0;
        }

        //$dist=$_POST['txtDist'];

        $fk_id_rider=$_POST['fk_idRider'];
        $array_riders_disponibles=get_riders($conexion_bd,$array_riders_disponibles);
        $error_rider_existe=false;
        foreach($array_riders_disponibles as $rider){
            if($rider['PK_Id']==$fk_id_rider){

                $error_rider_existe=true;
                $error_rider_existe_msg="Rider no encontrado en el sistema";
            }
        }

        if($fk_id_rider!=""&&$fk_id_rider>0&&$fk_id_rider!=null){
            $pedidos_por_rider=get_pedidos_por_rider($conexion_bd,$array_pedido,$fk_id_rider);
            $error_rider_ocupado=false;
            $cont=0;
            foreach($pedidos_por_rider as $pedido_por_rider){
                if($pedido_por_rider["Estado"]>0){
                    if($cont>1){
                        $error_rider_ocupado=true;
                        $error_rider_ocupado_msg="Rider ocupado";
                    }
                    $cont+=1;
                }
            }
        }

        if(!$error_ref_existe){
            $ref=$_POST['id'];
        }
        if($_POST['select_RIDER_MODAL']!=null){
            $nombre_apellidos= explode(" ",$_POST['select_RIDER_MODAL']);


            $nombre=$nombre_apellidos[0];
            $apellido=$nombre_apellidos[1];
            $array_riders_new=array();
            $ID_RIDER_NOMBRE_APELLIDOS=get_ID_riders_nombre_apellido($conexion_bd,$nombre,$apellido,$array_riders_new);
            $fk_id_rider=$ID_RIDER_NOMBRE_APELLIDOS[0]['PK_Id'];
        }

        $date_creacion=strtotime($_POST['date_crecion']);
        var_dump($pedido_existe);
        if($pedido_existe||$existe_pedido_id && $error_rider_existe && !$error_rider_ocupado){
            $error_ref_exist_msg="";
            $error_rider_existe_msg="";
            $pedido=new Pedido($id_pedido,$ref,$dir_recog,$date_recog,$dir_entreg,$date_entreg,$tiempo,$estado,$dist,$date_creacion,$fk_id_rider);
            actualizar_pedido($conexion_bd,$pedido);
            $pedido=get_pedido($conexion_bd,$array_pedido,$id_pedido);
        }else{
            if(!$error_estado && !$error_ref_vacia && !$error_rider_ocupado && !$pedido_existe){
                $pedido=new Pedido($id_pedido,$ref,$dir_recog,$date_recog,$dir_entreg,$date_entreg,$tiempo,$estado,$dist,$date_creacion,$fk_id_rider);
                guardar_pedido($conexion_bd,$pedido);
                $pedido=get_pedido($conexion_bd,$array_pedido,$id_pedido);
            }
        }

    }
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


function get_pedido_ref($conexion_bd,$array_pedido,$ref){
    $query="SELECT PK_id FROM PEDIDO WHERE Referencia=".$ref;
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




function get_pedidos_por_rider($conexion_bd,$array_pedido,$id_rider){
    $query="SELECT * FROM PEDIDO WHERE FK_ID_Rider=".$id_rider;
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
function get_pedido_por_id($conexion_bd,$array_pedido,$id_pedido){
    $query="SELECT * FROM PEDIDO WHERE PK_id=".$id_pedido;
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
function get_ID_riders_nombre_apellido($conexion_bd,$nombre,$apellido,$array_riders){
    $query="SELECT PK_Id FROM RIDER WHERE nombre =\"". $nombre."\" AND apellidos=\"".$apellido."\"";
    $res_riders = mysqli_query($conexion_bd, $query);
    if($res_riders === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_rider = $res_riders->fetch_assoc()){
        $array_riders[]=$row_rider;
    }
    return $array_riders;
}
function get_riders_disponibles($conexion_bd,$array_riders){
    $res_riders = mysqli_query($conexion_bd, "SELECT * FROM RIDER LEFT JOIN PEDIDO ON PEDIDO.FK_ID_Rider=RIDER.PK_Id WHERE PEDIDO.PK_id is null");
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

        $query_desactivar_cheks = "SET FOREIGN_KEY_CHECKS=0";
        mysqli_query($conexion_bd, $query_desactivar_cheks);
        $id_pedido_insert = $pedido->get_id();
        $estado_pedido_insert = $pedido->get_estado();
        if($estado_pedido_insert=="PENDIENTE"){
            $estado_pedido_insert=0;
        }
        if($estado_pedido_insert=="RECOGIDO"){
            $estado_pedido_insert=1;
        }
        if($estado_pedido_insert=="ENTREGADO"){
            $estado_pedido_insert=2;
        }
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
        $date_creacion_insert=date('Y-m-d\TH:i', $date_creacion_insert);



        $query = "INSERT INTO PEDIDO (PK_id,Estado,Direccion_recogida,Hora_recogida,Direccion_entrega,Hora_entrega,Tiempo_entrega,Distancia,FK_ID_Rider,Referencia,Fecha_creacion)
          VALUES ('$id_pedido_insert','$estado_pedido_insert','$dir_recogida_insert','$hora_recogida', '$dir_entrega_insert','$hora_entrega','$tiempo_insert','$distancia','$id_rider_insert','$referencia_insert','$date_creacion_insert')";
        //echo($query);
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
        //echo 'Pedido insertado con PK ' . $pk_id_pedido . PHP_EOL;
        $query_activar_cheks = "SET FOREIGN_KEY_CHECKS=1";
        mysqli_query($conexion_bd, $query_activar_cheks);



}



function actualizar_pedido($conexion_bd,$pedido){


    $distancia = $pedido->get_dist();
    if ($distancia == "-" || $distancia == "") {
        $distancia = null;
    }
    $hora_recogida = $pedido->get_Hora_recogida();
    if ($hora_recogida == "-") {
        $hora_recogida = null;
    }
    $hora_entrega = $pedido->get_Hora_entrega();
    if ($hora_entrega == "-") {
        $hora_entrega = null;
    }

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

    if($estado_pedido_insert=="PENDIENTE"){
        $estado_pedido_insert=0;
    }
    if($estado_pedido_insert=="RECOGIDO"){
        $estado_pedido_insert=1;
    }
    if($estado_pedido_insert=="ENTREGADO"){
        $estado_pedido_insert=2;
    }
    $query = "UPDATE PEDIDO SET Estado='$estado_pedido_insert',Direccion_recogida='$dir_recogida_insert',Hora_recogida='$hora_recogida',Direccion_entrega='$dir_entrega_insert',Tiempo_entrega='$tiempo_insert',Distancia='$distancia',FK_ID_Rider='$id_rider_insert',Referencia='$referencia_insert'
    WHERE PK_id='$id_pedido_insert'";

/*
    $query = "INSERT INTO PEDIDO (PK_id,Estado,Direccion_recogida,Hora_recogida,Direccion_entrega,Hora_entrega,Tiempo_entrega,Distancia,FK_ID_Rider,Referencia,Fecha_creacion)
          VALUES ('$id_pedido_insert','$estado_pedido_insert','$dir_recogida_insert','$hora_recogida', '$dir_entrega_insert','$hora_entrega','$tiempo_insert','$distancia','$id_rider_insert','$referencia_insert','$date_creacion_insert')";

*/
    $res_insert = mysqli_query($conexion_bd, $query);
    if ($res_insert === false) {
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    $query_activar_cheks = "SET FOREIGN_KEY_CHECKS=1";
    mysqli_query($conexion_bd, $query_activar_cheks);



}


    function get_pedidos($conexion_bd, $array_pedidos){
        $res_pedidos = mysqli_query($conexion_bd, "SELECT * FROM PEDIDO p LEFT JOIN RIDER r ON p.FK_ID_Rider=r.PK_Id");
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

