<?php

$array_pedidos=array();
$array_riders=array();

//mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT);
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

if(empty($_REQUEST["page"])||$_REQUEST["page"]==""||$_REQUEST["page"]<"1"){
    $_REQUEST["page"]="1";
}
$pedidos_encontrados=mysqli_query($conexion_bd,"SELECT * FROM PEDIDO p LEFT JOIN RIDER r ON p.FK_ID_Rider=r.PK_Id");
$num_reg=@mysqli_num_rows($pedidos_encontrados);

$pedidos_pag="1";
$page=$_REQUEST["page"];

if(is_numeric(($page))){
    $inicio=(($page-1)*$pedidos_pag);
}else {
    $inicio = 0;
}
    $query_busqueda_pedidos_limit="SELECT * FROM PEDIDO LIMIT $inicio,$pedidos_pag";
    echo($query_busqueda_pedidos_limit);
    $busqueda_pedidos=mysqli_query($conexion_bd,$query_busqueda_pedidos_limit);

    $pages=ceil(($num_reg/$pedidos_pag));
    echo($page). PHP_EOL;
    echo($pages). PHP_EOL;




// Este controlador se encarga de mostrar la vista de Listado de Pedidos

/**
 * 1. Obtener pedidos
 * Necesitaremos pasar de algún modo los filtros recibidos por $_GET para filtrar la búsqueda
 * Puede ser un array $filtros, enviar varios parámetros... lo que mejor te venga
 */
 //print_r($_GET);
$filtros=array();
$Ref=null;
$Rider=null;
$Estado=null;
//var_dump(($_GET));
    $keys_get=array_keys($_GET);
    //print_r($keys_get);
    if(in_array("txReferencia",$keys_get)){
        $Ref=addslashes($_GET['txReferencia']);
    }
    if(in_array("selRider",$keys_get)){
    $Rider=$_GET['selRider'];
    }

    if(in_array("selEstado",$keys_get)){
    $Estado=$_GET['selEstado'];
    }

    if($Ref!=null || $Ref!="" || $Ref!="-"){
        $filtros["REF"]=$Ref;
        //var_dump($filtros["REF"]);
    }
    if($Rider!=null || $Rider!="" || $Rider!="-"){
        $filtros["RID"]=$Rider;
    }
    if($Estado!=null || $Estado!="" || $Estado!="-"){
        $filtros["EST"]=$Estado;
    }
    if(in_array("pedidos_finalizados",$keys_get)){
        $finalizados=$_GET['pedidos_finalizados'];
        $filtros["FIN"]=$finalizados;
    }




/*
    if($Estado!=null || $Estado!=""){
    if ($Estado==0) {
        $filtros["EST"]='PENDIENTE';
    }elseif ($Estado==1) {
        $filtros["EST"]='RECOGIDO';
    }elseif ($Estado==2) {
        $filtros["EST"]='ENTREGADO';
    }
}
 */
//print_r($filtros);
$res_pedidos=get_pedidos($conexion_bd,$array_pedidos,$filtros);
//print_r($res_pedidos);
//$res_pedidos = $base_datos->get_pedidos($filtros);

/*
$res_pedidos = array(
    array(
        'REFERENCIA'     => 'REF',
        'FECHA_CREACION' => date('Y-m-d H:i:s'),
        'RIDER'          => 'Paco',
        'ESTADO'         => 'Pendiente'
    )
);
*(
/**
 * 2. Obtener riders para el seleccionable del filtro
 */
//$res_riders = $base_datos->get_riders();
$res_riders=get_riders($conexion_bd,$array_riders);
//$res_riders = array();

/**
 * 3. Obtener estados para el seleccionable del filtro
 */
//$res_estados = $base_datos->get_estados_pedidos();
$res_estados = array();
$res_estados[0]="PENDIENTE";
$res_estados[1]="RECOGIDO";
$res_estados[2]="ENTREGADO";


// Todas las variables creadas en controlador están disponibles en la vista HTML
// Estamos "incluyendo" el contenido de la vista en este script
require_once 'views/listado.php';








function get_pedidos($conexion_bd,$array_pedidos,$filtros){
    $query="SELECT * FROM PEDIDO p LEFT JOIN RIDER r ON p.FK_ID_Rider=r.PK_Id";
    /*
    echo($filtros['REF']."\n");
    echo($filtros['RID']."\n");
    echo($filtros['EST']."\n");
    */
    $filtro_keys=array_keys($filtros);

    if($filtros["REF"]!="" && $filtros["REF"]!="-" || $filtros["RID"]!="" && $filtros["RID"]!="-" || $filtros["EST"]!="" && $filtros["EST"]!="-" || $filtros['FIN']=="on"){
        $query.=" WHERE ";
        //print_r($filtros);
        $nombre_apellidos = explode(" ", $filtros["RID"]);
        //var_dump($_GET);
        foreach($filtro_keys as $filtro) {
            if ($filtro == "REF") {
                if ($filtros[$filtro] != '-' && $filtros[$filtro] != "") {
                    $query .= "Referencia like '%" . $filtros[$filtro]."%'";
                    $query .= " AND ";
                }
            }

            if ($filtro == "RID") {
                if ($filtros[$filtro] != '-' && $filtros[$filtro] != "") {
                    $query .= "r.nombre='" . $nombre_apellidos[0]."'";
                    $query .= " AND r.apellidos='". $nombre_apellidos[1]."'";
                    $query .= " AND ";
                }

            }
            if ($filtro == "EST") {
                if ($filtros[$filtro] != '-' && $filtros[$filtro] != "") {
                        if ($filtros[$filtro]=="PENDIENTE") {
                            $filtros["EST"]=0;
                        }elseif ($filtros[$filtro]=="RECOGIDO") {
                            $filtros["EST"]=1;
                        }elseif ($filtros[$filtro]=="ENTREGADO") {
                            $filtros["EST"]=2;
                        }
                    $query .= "Estado=" . $filtros[$filtro];
                    $query .= " AND ";
                }
            }
            if ($filtro == "FIN") {
                if ($filtros[$filtro] == 'on') {
                    var_dump("HOLA");
                    $query .= "Estado=" . "2";
                    $query .= " AND ";
                }
            }
        }
        $query = substr($query, 0, -4);
        //echo($query);
    }
/*
    echo("----------------- \n");
    echo($query);
    echo("----------------- \n");
*/
        #p.Referencia=".$filtros['REF']." AND p.FK_ID_RIDER=".$filtros['RID']." AND p.Estado=".$filtros['EST'];

    $res_pedidos = mysqli_query($conexion_bd, $query);
    if($res_pedidos === false){
        echo 'Query error: ' . mysqli_error($conexion_bd);
        exit;
    }
    while($row_pedido = $res_pedidos->fetch_assoc()){
        $array_pedidos[]=$row_pedido;
    }
    return $array_pedidos;
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